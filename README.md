# Index Numbers plugin

This DokuWiki syntax plugin creates counters with prefixes, for numbering
images and tables with indexes like "Tab. 1" or "Fig. 3".
Individual counters can be referenced.

## Installation
Copy the "indexnumber" folder into the `lib/plugins` folder of your DokuWiki installation.

## Tag syntax
Syntax for **creating a counter number** is `<idxnum countername id>`.
`countername` is an arbitrary string like "Tab." or "Fig." that must not contain
numbers. All `idxnum` tags with the same counter name will produce sequential
numbers. `id` must be a number. It is used for referencing the generated number
and can be omitted if you don't plan to reference this counter number.

Syntax for **referencing a counter number** is `<idxref countername id>`.
`countername id` must be a valid string from an `idxnum` tag that comes *before*
the reference. The `idxref` tag will insert the counter prefix and counter
number or an error message if the referenced counter was not found.


