# Attribute Type

Authors: Thelia <info@thelia.net>, Gilles Bourgeat <gbourgeat@openstudio.fr>

* This module allows you to add to your attributes the attributes types.
* Example : Color, Image link to the textures ...
* An attribute can have several types.
* An attribute type can have values or not.
* Values can be unique by language.

## Compatibility

Thelia > 2.1

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ```AttributeType```.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/attribute-type-module:~1.0
```

## Usage

* Once activated, click on the configure button for add or edit the attributes types.
* For associate an attribute to an attribute type, edit an attribute.

## Hooks

### backoffice :
- attribute-type.form-top (in form : create, update, attribute type) (params : attribute_type_id)
- attribute-type.form-bottom (in form : create, update, attribute type) (params : attribute_type_id)
- attribute-type.configuration-top
- attribute-type.configuration-bottom
- attribute-type.configuration-action (by attribute type) (params : attribute_type_id)
- attribute-type.configuration-js

## Loop

### attribute_type

#### Input arguments

|Argument |Description |
|---      |--- |
|**id**   | A single or a list of ids. |
|**exclude_id** | A single or a list of ids. |
|**slug** | String |
|**attribute_id** | A single or a list of attributes ids. |

#### Output arguments

|Variable       |Description |
|---            |--- |
|$ID            | The attribute type id |
|SLUG      | The attribute type slug |
|TITLE    | The attribute type title |
|DESCRIPTION    | The attribute type description |
|CSS_CLASS    | The attribute type css class |
|PATTERN    | The attribute type pattern |
|INPUT_TYPE    | The attribute type input type |
|MIN    | The attribute type minimum value |
|MAX    | The attribute type maximum value |
|STEP    | The attribute type step value |
|IS_MULTILINGUAL_ATTRIBUTE_AV_VALUE    | Indicates whether the values are unique for each language |
|HAS_ATTRIBUTE_AV_VALUE    | Indicates whether the type attribute has values for each attribute av |

### attribute_extend_attribute_type

Extends the Thelia loop : [Attribute](http://doc.thelia.net/en/documentation/loop/attribute.html)

#### Other input arguments

|Argument |Description |
|---      |--- |
|**attribute_type_id**   | A single or a list of attributes type ids. |

#### Other output arguments

* The attributes types associated.
* The variable name is equal to the name of the slug,
* The value is boolean, true for associated, false for unassociated.

#### Example
```smarty
    {loop name="attribute_extend_attribute_type" type="attribute_extend_attribute_type-payment" attribute_type_id="1,2,3"}
        {$TITLE} </br>

        {if $COLOR}
            The attribute has type color
        {/if}

        {if $MY_ATTRIBUTE_TYPE}
            The attribute has type "My attribute type"
        {/if}
    {/loop}
 ```

### attribute_availability_extend_attribute_type

Extends the Thelia loop : [Attribute availability](http://doc.thelia.net/en/documentation/loop/attribute_availability.html)

#### Other output arguments

* The attributes types associated.
* The variable name is equal to the name of the slug,
* The variable contains the value.

#### Example
```smarty
    title : color : my attribute type
    {loop name="attribute_availability_extend_attribute_type" type="attribute_availability_extend_attribute_type" attribute_id="1"}
        {$TITLE} : {$COLOR} : {$MY_ATTRIBUTE_TYPE} </br>
    {/loop}
```

