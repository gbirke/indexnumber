# Index numbers plugin

This DokuWiki syntax plugin creates counters with prefixes, for numbering
and labeling images and tables with indexes like "Tab. 1: Types of animals"
or "Fig. 3". The content between the `idxnum` tags will be wrapped in a 
`<div>` tag in the HTML output.

Individual counters and their prefix can be referenced with the
[indexreference](https://github.com/gbirke/indexreference) plugin.

## Installation
Copy the `indexnumber` folder into the `lib/plugins` folder of your DokuWiki installation.

If you want to insert specific counter names (see below), go to the Wiki 
configuration page and edit the field for index numbers configuration. 
Put each counter name on a new line.

If you want to have separator chars between the index number and the description 
you can also configure that on the wiki configuration page.

## Tag syntax
Syntax for creating a counter number is `<idxnum countername #id Description>`.  
`countername` is an arbitrary string like "Tab." or "Fig." that must not contain
the "#" character. All `idxnum` tags with the same counter name will produce sequential
numbers.  
`#id` must be a number, prefixed by the # char. It can be used for referencing 
the generated number with the indexreference plugin.  
`Description` is an arbitrary description and will be added to the output after
the index number. You can leave out the description but if you have a description 
you **must** have an id.

Please note that `idxnum` tags cannot be nested!

### Example Page

    This is the first image:
    <idxnum Fig.>
    {{computer.jpg}}
    </idxnum>

    This is the second image, with a description. Note that the id is arbitrary
    <idxnum Fig. #99 A beautiful tree>
    {{tree.jpg}}
    </idxnum>

    Interspersed table with a different counter:
    <idxnum Tab. #1 Some numbers>
    ^Foo^Bar^
    |42|47|
    |7|11|
    |6|0|
    </idxnum>

    Third image. Note how the counter is independent from id and the table counter
    <idxnum Fig. #4 Snowy landscape>
    {{snow.jpg}}
    </idxnum>

## Changelog

### Version 1.0
First release

### Version 1.1
Toolbar buttons



