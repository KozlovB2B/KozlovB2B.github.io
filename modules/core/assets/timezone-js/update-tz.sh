##!/bin/bash

# NOTE: Run from your webroot

# Create the /tz directory
mkdir tz

# Download the latest Olson files
curl ftp://ftp.iana.org/tz/tzdata-latest.tar.gz -o tz/tzdata-latest.tar.gz

# Expand the files
tar -xvzf tz/tzdata-latest.tar.gz -C tz

# Optionally, you can remove the downloaded archives.
rm tz/tzdata-latest.tar.gz