<?php
/**
 * AxelMedia/Object
 *
 * Licensed under The MIT License
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
 namespace AxelMedia;

 class Object extends \ArrayObject
 {
     /**
      * Create new object
      *
      * @param mixed $value Pre-populate object with this key-value
      */
     public function __construct($input = array())
     {
         parent::__construct($input, self::ARRAY_AS_PROPS);
     }

     /**
      * Set object item
      *
      * @param mixed $name   he data key or array
      * @param mixed $value The data value
      *
      * @return object      Current object
      */
     public function set($name, $value = null)
     {
         $num = func_num_args();
         if (1 === $num) {
             if (is_array($name) || $name instanceof \Traversable) {
                 foreach($name as $key => $val) {
                     parent::offsetSet($key, $val);
                 }
             } elseif($this->isJSON($name)) {
                 foreach(json_decode($name, true) as $key => $val) {
                     parent::offsetSet($key, $val);
                 }
             }
         } elseif($num > 1) {
             parent::offsetSet($key, $value);
         }

         return $this;
     }

     /**
      * Get object item for key
      *
      * @param string $key     The data key
      * @param mixed  $default The default value to return if data key does not exist
      *
      * @return mixed          The key's value, or the default value
      */
     public function get($name, $default = null)
     {
         return parent::offsetExists($name) ? parent::offsetGet($name) : $default;
     }

     /**
      * Does this object have a given key?
      *
      * @param string $key The data key
      *
      * @return bool
      */
     public function has($name)
     {
         return parent::offsetExists($name);
     }

     /**
      * Remove item from object
      *
      * @param string $key The data key
      */
     public function remove($name)
     {
         parent::offsetUnset($name);
     }

     /**
      * Remove all items from object
      */
     public function clear()
     {
         foreach (parent::getArrayCopy() as $key => $val) {
             parent::offsetUnset($key);
         }
     }

     /**
      * Get all items in object
      *
      * @return array The object's source data
      */
     public function all()
     {
         return parent::getArrayCopy();
     }

     /**
      * Get object keys
      *
      * @return array The object's source data keys
      */
     public function keys()
     {
         return array_keys(parent::getArrayCopy());
     }

     /**
      * Get object values
      *
      * @return array The object's source data values
      */
     public function values()
     {
         return array_values(parent::getArrayCopy());
     }

     /**
      * Get first object value
      *
      * @return mixed The object's first source data value
      */
     public function first()
     {
         return reset(parent::getArrayCopy());
     }

     /**
      * Get last object value
      *
      * @return mixed The object's last source data value
      */
     public function last()
     {
         return end(parent::getArrayCopy());
     }

     /**
      * Returns true if $value is present in this object.
      *
      * @param mixed $value The value to check for
      *
      * @return bool true if $value is present in this object
      */
     public function contains($value, $strict = true)
     {
         return in_array($value, parent::getArrayCopy(), $strict);
     }

     /**
      * Add item to object, replacing existing items with the same data key
      *
      * @param array $items Key-value array of data to append to this object
      *
      * @return object Current object
      */
     public function replace(array $items)
     {
         foreach ($items as $key => $value) {
             parent::offsetSet($key, $value);
         }

         return $this;
     }

     /**
      * Executes the passed callable for each of the elements in this object
      *
      * @param callable $call Callable function that will receive each of the elements in this object
      *
      * @return object Current object
      */
     public function each(callable $call)
     {
         if ($call instanceof \Closure) {
             $call = $call->bindTo($this);
         }

         foreach (parent::getArrayCopy() as $key => $value) {
             $call($value, $key);
         }

         return $this;
     }

     /**
      * Executes the passed callable for each of the elements in this object
      *
      * @param callable $call Callable function that will receive each of the elements in this object
      *
      * @return object Current object
      */
     public function map(callable $call)
     {
         if ($call instanceof \Closure) {
             $call = $call->bindTo($this);
         }

         foreach (parent::getArrayCopy() as $key => $value) {
             parent::offsetSet($key, $call($value, $key));
         }

         return $this;
     }

     /**
      * Get last object value
      *
      * @return mixed The object's last source data value
      */
     public function slice($offset = 0, $length = null, $preserve_keys = true)
     {
         return array_slice(parent::getArrayCopy(), $offset, $length, $preserve_keys);
     }

     /**
      * Get all data to Array
      *
      * @return array
      */
     public function toArray()
     {
         return parent::getArrayCopy();
     }

     /**
      * Get all data to JSON
      *
      * @param int $options json_encode options
      * @param int $depth json_encode depth
      *
      * @return string JSON string
      */
     public function toJSON($options = 0)
     {
         return json_encode(parent::getArrayCopy(), $options);
     }

     /**
      * Get all data to Serialized
      *
      * @param string
      */
     public function toSerialize()
     {
         return parent::serialize();
     }

     /**
      * Export to string
      *
      * @param string
      */
     public function toString()
     {
         return var_export(parent::getArrayCopy(), true);
     }

     /**
      * Detect empty data
      *
      * @param bool
      */
     public function isClean()
     {
         $array = parent::getArrayCopy();
         return empty($array);
     }

     /**
      * Detect data is empty
      *
      * @param string $key The data key
      *
      * @return bool
      */
     public function isEmpty($name)
     {
         if (parent::offsetExists($name)) {
             $value = parent::offsetGet($name);
             return ('' === $value || null === $value || false === $value);
         }

         return true;
     }


     /********************************************************************************
      * Overrides
      *******************************************************************************/

     /**
      * Does this object have a given key?
      *
      * @param  string $name The data key
      *
      * @return bool
      */
     public function __isset($name)
     {
         return parent::offsetExists($name);
     }

     /**
      * Remove item from object
      *
      * @param string $name The data key
      */
     public function __unset($name)
     {
         return parent::offsetUnset($name);
     }

     /**
      * Collection cloning
      *
      * @param object Cloned Collection object
      */
     public function __clone()
     {
     }

     /**
      * Get dump data
      *
      * @param array
      */
     public function __debugInfo()
     {
         return parent::getArrayCopy();
     }



     /********************************************************************************
      * Private Methods
      *******************************************************************************/

     /**
      * Check JSON string
      *
      * @return bool
      */
     private function isJSON($value)
     {
         if (!is_string($value)) {
             return false;
         }

         $result = json_decode($value);
         return (json_last_error() === 0);
     }
 }
