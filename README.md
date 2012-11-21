# Index Numbers plugin

This DokuWiki syntax plugin creates counters with prefixes, for numbering
and labeling images and tables with indexes like "Tab. 1: Types of animals"
or "Fig. 3".
Individual counters can be referenced with the indexreference plugin.

## Installation
Copy the "indexnumber" folder into the `lib/plugins` folder of your DokuWiki installation.

## Tag syntax
Syntax for creating a counter number is `<idxnum countername #id Description>`.
`countername` is an arbitrary string like "Tab." or "Fig." that must not contain
the "#" character. All `idxnum` tags with the same counter name will produce sequential
numbers. `#id` must be a number, prefixed by the # char. It can be used for
referencing the generated number with the indexreference plugin. 'Description' is an
arbitrary description and will be added to the output after the index number.
You can leave out the description but if you have a description you **must** have
an id.




