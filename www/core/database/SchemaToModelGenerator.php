<?php

namespace app\core\database;

/**
 * Usage:
 * $db = Application::$app->db;
 * $generator = new SchemaToModelGenerator($db, $outputPath);
 * $generator->generate();
 * echo "Modelos generados exitosamente en: $outputPath\n";
 */

class SchemaToModelGenerator
{
    private $pdo;
    private string $outputPath;

    public function __construct($pdo, string $outputPath)
    {
        $this->pdo = $pdo;
        $this->outputPath = $outputPath;
    }

    private function deleteLastChar(string $str): string
    {
        if (substr($str, -1) === 's') {
            return substr($str, 0, -1);
        } else {
            return $str;
        }
    }

    public function generate(): void
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $columns = $this->getColumns($table);
            $this->createModelFile($table, $columns);
        }
    }

    private function getTables(): array
    {
        $stmt = $this->pdo->query("SHOW TABLES");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getColumns(string $table): array
    {
        $stmt = $this->pdo->prepare("DESCRIBE $table");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function createModelFile(string $table, array $columns): void
    {
        $className = $this->getClassName($table);
        $fillable = $this->getFillableString($columns);

        $content = $this->getModelTemplate($className, $table, $fillable);

        $filePath = $this->outputPath . '/' . $className . '.php';
        if (!file_exists($filePath)) {
            file_put_contents($filePath, $content);
        }
    }

    private function getClassName(string $table): string
    {
        return $this->deleteLastChar(ucfirst($table));
    }

    private function getFillableString(array $columns): string
    {
        $fillableColumns = array_filter($columns, function ($column) {
            return !in_array($column, ['id', 'created_at', 'updated_at']);
        });
        return "'" . implode("', '", $fillableColumns) . "'";
    }

    private function getModelTemplate(string $className, string $table, string $fillable): string
    {
        return <<<EOT
<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class $className
 * 
 * This class represents the '$table' table in the database.
 * 
 */
class $className extends Model
{
    protected \$table = '$table';
    protected \$fillable = [$fillable];

    public function __construct()
    {
        parent::__construct();
        // TODO: Crear reglas de negocio aca.
        // \$this->rules->add(
        //     'column',
        //     (new Rules())
        //         ->rules(\$this->table, 'column', 'message')
        //         ->get()
        // );
    }
}
EOT;
    }
}
