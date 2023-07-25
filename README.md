# astrio_php_test

Astrio PHP Developer Test Tasks

## Task 1

An array of "categories" is given. Each category has the following parameters:
"id" - unique numeric identifier of categories
"title" - category name
"children" - child categories (an array of categories)

The nesting of a category is unlimited (child categories can have their own nested categories, etc).

Example array :

```
$categories = array(
	array(
   	"id" => 1,
   	"title" =>  "Обувь",
   	'children' => array(
       	array(
           	'id' => 2,
           	'title' => 'Ботинки',
           	'children' => array(
               	array('id' => 3, 'title' => 'Кожа'),
               	array('id' => 4, 'title' => 'Текстиль'),
           	),
       	),
       	array('id' => 5, 'title' => 'Кроссовки',),
   	)
	),
	array(
   	"id" => 6,
   	"title" =>  "Спорт",
   	'children' => array(
       	array(
           	'id' => 7,
           	'title' => 'Мячи'
       	)
   	)
	),
);
```

You need to write a function `searchCategory($categories, $id)`, which returns the name of the category by category ID.

## Task 2

Three tables are given:

table `worker` with data - id (worker id), first_name (first name), last_name (last name)
table `child` (child) with data - worker_id (worker's id), name (child's name)
table `car` (car) with data - worker_id (worker id), model (car model)

Structure of Tables:

```
CREATE TABLE `worker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

```
CREATE TABLE `car` (
  `user_id` int(11) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
   FOREIGN KEY (user_id) REFERENCES worker(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

```
CREATE TABLE `child` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES worker(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

It is necessary to write one SQL query that returns: the first and last names of all employees, a list of their children separated by commas and the brand of their car. Only those employees who have or had a car should be selected (if there was a car and then it is gone, the model field becomes null).

## Task 4

Write a function that takes as input an array of opening or closing tags (e.g. `<a>, </td>` ), and returns the result of a correctness check: i.e. whether the sequence of tags accepted by the function is the structure of a correct HTML document. For example, the sequence `<a>, <div>, </div>, </a>, <span>, </span>` is a valid structure, but the sequence последовательность `<a>, <div>, </a>` is an invalid structure.
It is necessary to use native PHP without using DOMDocument, Simplexml, etc. libraries.

## Task 5

Write a class-wrapper for the "Box" storage.
The storage can set data (`setData($key, $value)`), get data (`getData($key)`), save data (`save()`) and load data (`load()`)

$key - arbitrary data identifier
$value - scalar data or array

The storage consists of:

- An interface describing methods for setting data, retrieving data, saving and loading data
- `AbstractBox` - abstract class containing the implementation of the necessary general methods
- `FileBox` - class extending AbstractBox abstract class. When `saving()` is called, saves the data specified in the class to a file. When `load()` is called, it retrieves data from the file.
- `DbBox` - class extends AbstractBox abstract class. When `saving()` is called, it saves the data specified in the class to the database. When calling `load()`, it retrieves data from the database.

The load function should return notyhing, it should only save the received data inside the object. The `getData($key)` function is used to get the data.

When saving data it is necessary to take into account the keys of new data and those data that are stored in the base (file), if a value with a key in the base (file) already exists, then you need to replace its value with a new one.

The `FileBox` and `DbBox` classes should be implemented in such a way that it is impossible to create more than one instance of each class.

General wishes on the fulfillment of all tasks: for 1 and 3 tasks you need to create one function with the necessary logic in a separate file, if you need to break any of the functions not several, do everything in the same file, for 2 tasks, you need to write one sql query in a separate file and nothing else, for 4 tasks in a separate folder to create the necessary classes. No index.php, html markup and moreover styles should not be added.

We wish you good luck in completing the assignment!

Thank you.

## Installation

### Prerequisites

1. PHP Version: Ensure that you have PHP installed on your system (PHP 8.1 or later).
2. Composer

## Installation steps

1. Open a terminal or command prompt
2. Clone this repo `https://github.com/deepydee/astrio_php_test.git`
3. Change your current directory to your project folder `cd astrio_php_test`
4. Run the following command to install PHPUnit `Composer install`
5. Run tests `composer test`
