# PreSell
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
