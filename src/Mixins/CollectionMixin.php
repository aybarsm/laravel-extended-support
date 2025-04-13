<?php

namespace Aybarsm\Laravel\Support\Mixins;

/** @mixin \Illuminate\Support\Collection */
class CollectionMixin
{
    /*
     * Implemented from: https://gist.github.com/brunogaspar/154fb2f99a7f83003ef35fd4b5655935?permalink_comment_id=4410835#gistcomment-4410835
     */
    public static function recursive(): \Closure
    {
        return function (?int $depth = null) {
            // Use the map method to iterate over the items in the collection.
            return $this->map(function ($item) use ($depth) {
                // If the depth is 0 or the item is not a collection, array, or object, return the item as-is.
                if (($depth === 0) || ! ($item instanceof \Illuminate\Support\Collection || is_array($item) || is_object($item))) {
                    return $item;
                }

                // Create a new anonymous class that extends the Collection class and overrides the __get and __set magic methods.
                // To be able to access the collection items as if they were properties of a object.
                return (new class(new static($item)) extends \Illuminate\Support\Collection
                {
                    public function __get($key)
                    {
                        return $this->get($key);
                    }

                    public function __set($key, $value)
                    {
                        $this->put($key, $value);
                    }
                })->recursive($depth - 1); // Apply the "recursive" method to the new Collection instance.
            });
        };
    }
}
