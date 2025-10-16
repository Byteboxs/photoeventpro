<?php

namespace app\core\model;

use app\core\Application;
use app\core\exceptions\ModelNotFoundException;
use app\core\collections\Map;
use app\core\exceptions\InvalidBuilderException;
use app\core\exceptions\ModelValidationException;
use app\core\exceptions\ModelValitationException;
use app\core\exceptions\ModelWriteException;
use Exception;

abstract class Model
{
    protected int $perPage = 10;
    protected string $primaryKey = 'id';
    protected $idValue;
    protected $connection = 'mysql';
    protected array $attributes = [];
    protected $fillable = [];
    protected Map $rules;
    protected $table = '';
    protected $builder;
    protected $fillableAttributes = [];
    private bool $fetcObject = true;
    protected $stmt = null;

    public function __construct()
    {
        // $this->attributes = [];
        if ($this->table === '') {
            $this->table = strtolower((new \ReflectionClass($this))->getShortName()) . 's';
        }
        $this->fillableAttributes = array_intersect_key($this->attributes, array_flip($this->fillable));
        $this->builder = Application::$app->builder;
        if ($this->builder === null) {
            throw new InvalidBuilderException('Esta intentando usar el Builder sin una conexión establecida.');
        }
        $this->rules = new Map();
        $this->idValue = $this->getIdValue();
    }
    public function activateFetchObject()
    {
        $this->fetcObject = true;
    }
    public function deactivateFetchObject()
    {
        $this->fetcObject = false;
    }
    private function getIdValue()
    {
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->attributes[$this->primaryKey];
        } else {
            return null;
        }
    }

    public function getAttributes()
    {
        $reflector = new \ReflectionClass($this);
        $propiedades = $reflector->getProperties();
        $atributos = [];
        foreach ($propiedades as $propiedad) {
            $propiedad->setAccessible(true); // Permitir acceso a atributos privados
            $atributos[$propiedad->getName()] = $propiedad->getValue($this);
        }

        return $atributos;
    }
    protected function fetch($stmt)
    {
        if ($this->fetcObject) {
            $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        }

        return $stmt->fetchAll();
    }

    protected function fetchResult($stmt)
    {
        $result = $this->fetch($stmt);
        if (!$result) {
            // throw new ModelNotFoundException("MODELO: No se encontraron resultados");
            return null;
        }
        return $result[0];
    }
    protected function fetchAllResult($stmt)
    {
        $result = $this->fetch($stmt);
        if (!$result) {
            // throw new ModelNotFoundException("MODELO: No se encontraron resultados");
            return [];
        }
        if (count($result) === 1) {
            return $result[0];
        } else {
            return $result;
        }
    }

    // abstract protected function setRules();
    public function getRules()
    {
        return $this->rules;
    }

    public function last(string $column = 'created_at')
    {
        $this->builder->select('*')->from($this->table);
        $this->builder->orderBy($column, 'desc');
        $this->builder->limit(1);
        $stmt = $this->builder->get();
        return $this->fetchResult($stmt);
    }

    public function all()
    {
        $this->builder->select('*')->from($this->table);
        $stmt = $this->builder->get();
        return $this->fetchAllResult($stmt);
    }

    public function find($id)
    {
        $stmt = $this->builder->table($this->table)->where($this->primaryKey, $id)->get();
        return $this->fetchResult($stmt);
    }

    public function findWhere(array $argumentos)
    {
        $this->builder->select('*')->from($this->table);
        if (is_array($argumentos)) {
            foreach ($argumentos as $key => $value) {
                if (is_array($value)) {
                    $this->builder->where($value);
                } else {
                    $this->builder->where($key, $value);
                }
            }
        }
        $stmt = $this->builder->get();
        return $this->fetchAllResult($stmt);
    }

    public static function create(array $attributes)
    {
        $className = static::class;
        $instance = null;
        $instance = new $className();
        foreach ($attributes as $key => $value) {
            $instance->$key = $value;
        }
        $validador = RulesValidator::make();
        $validador->setModel($instance);
        $hasErrors = $validador->hasErrors();
        if ($hasErrors) {
            $errors = $validador->getErrors();
            $instance = false;
            throw new ModelValidationException($errors);
        } else {
            $instance->save();
        }
        return $instance;
    }

    public function save()
    {
        $this->fillableAttributes = array_intersect_key($this->attributes, array_flip($this->fillable));
        if (count($this->fillableAttributes) === 0) {
            throw new ModelWriteException(static::class, json_encode($this->fillableAttributes));
        }

        $this->idValue = $this->getIdValue();
        $validador = RulesValidator::make();
        $validador->setModel($this);
        $hasErrors = $validador->hasErrors();

        if ($hasErrors) {
            $errors = $validador->getErrors();
            throw new ModelValidationException($errors);
        }

        if (isset($this->attributes[$this->primaryKey])) {
            $this->builder->update($this->table, $this->idValue, $this->fillableAttributes, $this->primaryKey)->get();
        } else {
            $this->builder->insert($this->table, $this->fillableAttributes)->get();
            return $this->last($this->primaryKey);
        }

        return $this;
    }

    public function findOrCreate($uniqueKey)
    {
        $this->fillableAttributes = array_intersect_key($this->attributes, array_flip($this->fillable));
        if (count($this->fillableAttributes) === 0) {
            throw new ModelWriteException(static::class, json_encode($this->fillableAttributes));
        }

        $validador = RulesValidator::make();
        $validador->setModel($this);
        $validador->hasErrors();
        $errors = $validador->getErrors()->toArray();
        $isDuplicate = $this->isDuplicateError($errors);

        if ($isDuplicate) {
            $stmt = $this->builder->table($this->table)->where($uniqueKey, $this->attributes[$uniqueKey])->get();
            return $this->fetchResult($stmt);
        } else {
            $this->save();
            return $this->last($this->primaryKey);
        }
    }

    protected function isDuplicateError(array $errors): bool
    {
        $duplicateKeywords = [
            'already exists',
            'duplicate',
            'unique',
            'constraint',
            'ya existe',
            'The value already exists.',
        ];

        foreach ($duplicateKeywords as $keyword) {
            foreach ($errors as $error) {
                if (stripos($error, $keyword) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    public function remove()
    {
        $this->idValue = $this->getIdValue();
        return $this->builder->delete($this->table)->where($this->primaryKey, $this->idValue)->get();
    }



    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function __set(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function toJson()
    {
        return json_encode($this->attributes);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function toPlainObject()
    {
        return json_decode(json_encode($this->attributes), FALSE);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function run($sql, $params = null)
    {
        return $this->builder->run($sql, $params);
    }

    public function hasOne(string $className, string $foreignKey, string $localKey) {}
    public function belongsTo(string $className, string $foreignKey, string $localKey) {}
}
