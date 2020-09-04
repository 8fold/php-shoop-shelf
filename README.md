# 8fold Shoop Shelf

Shoop Shelf is an extension of [8fold Shoop](https://github.com/8fold/php-shoop) it is for Shoop-like objects that aren't able to fit on the Shoop table.

To be classified as a Shoop type the specified type must:

a. be inferrable from the given value (Markdown, for example, cannot be inferred from a string the same way JSON can), and
b. have a rational representation in all other Shoop types.

These extensions do not match this criteria; however, they can be:

1. can be just as useful and
2. follow a similar development strategy and pattern from Shoop.
