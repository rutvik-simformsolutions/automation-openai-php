#!/bin/sh
git log --no-merges --date=format:"%d/%m/%Y" --pretty=format:"%s" | head -n 10 >| output.txt