# PreSell Module for Magento 2
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Space48/PreSell/badges/quality-score.png?b=master&s=7210069e74b8b2e80850a97f722117ac72655eb6)](https://scrutinizer-ci.com/g/Space48/PreSell/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Space48/PreSell/badges/build.png?b=master&s=36a86ee32d2c7ad2d3cce148ad8d8faac2d70e59)](https://scrutinizer-ci.com/g/Space48/PreSell/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Space48/PreSell/badges/coverage.png?b=master&s=4a30cd427d9faf4f0ead350e655f1659d3e3c2d1)](https://scrutinizer-ci.com/g/Space48/PreSell/?branch=master)

This module changes the logic that determines if the "Add to Cart" button and "Notify Me When Back In Stock" button shows. It does this by adding two new attributes:

- pre_sell (yes/no int)
- pre_sell_qty (text int)

**The Add to cart logic is the following:**

- If item has qty > 0 show button
- If item has qty < 0 but has pre_sell qty > 0 AND pre_sell set to "Yes" show button

**The notify me button logic is the following:**

- If item has qty < 0 show the notify button

**Grouped product qty box logic is the following**

- If item has qty > 0 show qty box
- If item has qty < 0 but has pre_sell qty > 0 AND pre_sell set to "Yes" show qty box
- If available_from_x date is set, show the availability message underneath each product name

## Installation

**Manually** 

To install this module copy the code from this repo to `app/code/Space48/PreSell` folder of your Magento 2 instance, then you need to run php `bin/magento setup:upgrade`

**Via composer**:

From the terminal execute the following to add presell repository to the repository list:

`composer config repositories.space48-presell vcs git@github.com:Space48/PreSell.git`

then execute the following command to install the module.

`composer require "space48/presell:{release-version}"`
