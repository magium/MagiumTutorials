# I Want To Know Why byText() Has Translation Support And byXpath() Does Not

It's actually pretty simple.  `byId()`, `byXpath()`, and `byCssSelector()` are essentially low level components.  They are there to do the dirty work.  `byText()` (and `containsText()`), on the other hand, are there for convenience.