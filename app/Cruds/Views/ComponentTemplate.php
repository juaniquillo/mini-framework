<?php

namespace App\Cruds\Views;

use Chatagency\CrudAssistant\DataContainer;

class ComponentTemplate extends DataContainer
{
    /**
     * Component namespace
     *
     * @var string
     */
    protected $namespace = 'dynamic.';
    
    /**
     * Vier type
     *
     * @var string
     */
    protected $viewTypeInternal;

    /**
     * Is Livewire component
     *
     * @var bool
     */
    protected $isLiveWireInternal = false;

    /**
     * Livewire key
     *
     * @var mixed
     */
    protected $livewireKeyInternal = null;

    /**
     * Sets template type.
     *
     * @param  string|null  $type
     * @return self
     */
    public function setType(string $type = null)
    {
        $this->viewTypeInternal = $type;

        return $this;
    }

    /**
     * Returns template type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->namespace && !$this->livewire()
            ? $this->namespace.$this->viewTypeInternal 
            : $this->viewTypeInternal;
    }

    /**
     * Is a Livewire component
     *
     * @param  string  $type
     * @return self
     */
    public function isLivewire(bool $livewire = true)
    {
        $this->isLiveWireInternal = $livewire;

        return $this;
    }

    /**
     * Returns if template is a livewire one.
     *
     * @return string
     */
    public function livewire()
    {
        return $this->isLiveWireInternal;
    }

    /**
     * Get the value of livewireKeyInternal
     */
    public function livewireKey()
    {
        return $this->livewireKeyInternal;
    }

    /**
     * Set the value of livewireKeyInternal
     *
     * @return  self
     */
    public function setLivewireKey($livewireKeyInternal)
    {
        $this->livewireKeyInternal = $livewireKeyInternal;

        return $this;
    }

    /**
     * Returns template attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes ?? [];
    }

    /**
     * Returns specific attribute.
     *
     * @param  string  $name
     */
    public function getAttribute(string $name)
    {
        return $this->getAttributes()[$name] ?? null;
    }

    /**
     * Returns template themes.
     *
     * @return array
     */
    public function getThemes()
    {
        return $this->theme ?? [];
    }

    /**
     * Returns specific theme.
     *
     * @param  string  $name
     */
    public function getTheme(string $name)
    {
        return $this->getThemes()[$name] ?? null;
    }

    /**
     * Returns template extra params.
     *
     * @return array
     */
    public function getExtras()
    {
        return $this->extra ?? [];
    }

    /**
     * Returns specific extra.
     *
     * @param  string  $name
     */
    public function getExtra(string $name)
    {
        return $this->getThemes()[$name] ?? null;
    }

    /**
     * Sets specific attribute.
     *
     * @param  string  $name
     * @param $value
     * @return self
     */
    public function setAttribute(string $name, $value)
    {
        $oldAttributes = $this->getAttributes();
        $oldAttributes[$name] = $value;
        $this->attributes = $oldAttributes;

        return $this;
    }

    /**
     * Sets attribute array
     *
     * @param  array  $attributes
     * @return self
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        return $this;
    }

    /**
     * Sets specific theme.
     *
     * @param  string  $name
     * @param $value
     * @return self
     */
    public function setTheme(string $name, $value)
    {
        $oldTHemes = $this->getTHemes();
        $oldTHemes[$name] = $value;
        $this->theme = $oldTHemes;

        return $this;
    }

    /**
     * Sets theme array
     *
     * @param  array  $themes
     * @return self
     */
    public function setThemes(array $themes)
    {
        foreach ($themes as $name => $value) {
            $this->setTHeme($name, $value);
        }

        return $this;
    }

    /**
     * Sets specific theme.
     *
     * @param  string  $name
     * @param $value
     * @return self
     */
    public function setExtra(string $name, $value)
    {
        $oldExtras = $this->getExtras();
        $oldExtras[$name] = $value;
        $this->theme = $oldExtras;

        return $this;
    }

    /**
     * Sets theme array
     *
     * @param  array  $themes
     * @return self
     */
    public function setExtras(array $extras)
    {
        foreach ($extras as $name => $value) {
            $this->setExtra($name, $value);
        }

        return $this;
    }

    /**
     * Get component namespace
     *
     * @return string
     */ 
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set component namespace
     *
     * @param string  $namespace Component namespace
     *
     * @return self
     */ 
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }
    
    /**
     * Returns the data array.
     *
     * @return array
     */
    public function toArray()
    {
        /**
         * Add component type
         */
        $this->addMetaData();

        return $this->data;
    }

    /**
     * To string method.
     *
     * @return string
     */
    public function __toString()
    {
        $this->addMetaData();
        
        return json_encode($this->data);
    }
    
    /**
     * @return void
     */
    protected function addMetaData()
    {
        $this->data['viewTypeInternal'] = $this->viewTypeInternal;
        $this->data['ComponentTypeInternal'] =  ucfirst(ucwords($this->viewTypeInternal));
    }
    
}
