# Magento 2 WhatsApp Integration

Adds WhatsApp contact points to a Magento 2 storefront: a floating chat button on every page, an inquiry button on product pages that pre-fills the product name and URL, and an assistance banner on category pages. Each element opens a `wa.me` link to the phone number you configure, with a pre-filled message. Nothing is sent from the server; the customer's own WhatsApp app or WhatsApp Web handles the conversation.

It suits stores that take product questions and orders over WhatsApp and want the button placed consistently without editing theme files. Works with the Hyva and Luma themes.

Product page: [kishansavaliya.com/magento-2-whatsapp.html](https://kishansavaliya.com/magento-2-whatsapp.html)

## Features

- Floating button on all pages except checkout with a configurable phone number, pre-filled message, hover text (shown on hover and keyboard focus, opening toward the page) and position (bottom right, bottom left, top right, top left). It uses z-index 30, hides while a drawer, dialog, modal, open search or mobile menu is shown (on phones and tablets while a form field has focus, and on phones while the filter panel is open), fades out while it would cover the layered navigation toggle, filter controls, toolbar sorter or pager (or any element with `data-panth-float-avoid`), and moves up above bottom notification bars through `--panth-bottom-bar-offset`. Floating elements share one bottom stack per side: back-to-top sits at the bottom, the WhatsApp button above it, the EU withdrawal tab (phones and tablets) above that. Each element adds `--panth-bottom-bar-offset` (bottom notification bars), the slot variables `--panth-float-slot-btt-<side>` and `--panth-float-slot-wa-<side>` of the elements below it, and `--panth-float-edge` (24px, 16px below 768px).
- Product page button with its own text and message template; `{product_name}` and `{product_url}` are replaced with the product being viewed. Four styles: solid, outline, icon only, text only.
- Category page banner with its own text and message; the current category name is appended to the message.
- Each of the three elements has its own enable switch and can be configured per default, website or store view.
- Optional extra CSS classes for all elements.
- Colours and sizes are defined in `etc/theme-config.json` and exposed as CSS variables through Panth Core, so a theme can override them without touching the templates.
- No database tables, no cron jobs, no console commands and no calls to the WhatsApp Business API.

## Compatibility

| | |
|---|---|
| Magento Open Source / Adobe Commerce | 2.4.4 to 2.4.8 |
| PHP | 8.1, 8.2, 8.3, 8.4 |
| Themes | Hyva and Luma |

The Composer package requires `magento/framework ^103.0`, `magento/module-backend ^102.0`, `magento/module-catalog ^104.0`, `magento/module-config ^101.2` and `magento/module-store ^101.1`.

## Requirements

- Magento 2.4.4 or later
- PHP 8.1 to 8.4
- `mage2kishan/module-core` (installed automatically by Composer; provides the shared "Panth Extensions" admin tab and the CSS variable output used for colours and sizes)
- A phone number registered with WhatsApp (a WhatsApp Business account is not required)

## Installation

```bash
composer require mage2kishan/module-whatsapp
bin/magento module:enable Panth_Core Panth_WhatsApp
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

`setup:di:compile` is only needed in production mode. The templates carry their own styles, so no static content deployment is required.

Check the result with:

```bash
bin/magento module:status Panth_WhatsApp
```

All three elements are switched off by default, so nothing appears on the storefront until you enable them in the configuration.

## Configuration

Go to **Stores > Configuration > Panth Extensions > WhatsApp Integration**.

Float Button Settings:

| Setting | Default | What it does |
|---|---|---|
| Enable WhatsApp Float Button | No | Shows the floating button on every page. |
| WhatsApp Phone Number | (empty) | Number with country code, for example `+1234567890`. Non-digits are stripped when the link is built. No WhatsApp element is shown until a number is set. |
| Default Message | Hi! I have a question about your products. | Pre-filled message for the floating button. |
| Button Text | Chat with Us | Text shown on hover. |
| Button Position | Bottom Left | Bottom right, bottom left, top right or top left. |

Product Page Settings:

| Setting | Default | What it does |
|---|---|---|
| Enable WhatsApp on Product Pages | No | Shows the button on product detail pages. |
| Button Text | Ask on WhatsApp | Label of the product page button. |
| Message Template | Hi! I'm interested in {product_name}. {product_url} | `{product_name}` and `{product_url}` are replaced for the product being viewed. |
| Button Style | Solid | Solid, outline, icon only or text only. Stores that still hold the older stored value `default` see it listed as "Default (Text Only, legacy value)"; it renders like text only and is kept when the section is saved. |

Category Page Settings:

| Setting | Default | What it does |
|---|---|---|
| Enable WhatsApp on Category Pages | No | Shows the assistance banner above the product listing. |
| Button Text | Chat with Us | Label of the banner button. |
| Message Template | Hi! I need help finding products in your store. | Pre-filled message; the current category name is appended. |

Advanced Styling:

| Setting | Default | What it does |
|---|---|---|
| Custom CSS Classes | (empty) | Extra CSS classes added to the WhatsApp elements, one per line. |

The phone number is shared by all three elements. Configuration paths start with `panth_whatsapp/general/`, `panth_whatsapp/product/`, `panth_whatsapp/category/` and `panth_whatsapp/advanced/`.

## Usage

- Floating button: rendered from the `default` layout handle in the `after.body.start` container whenever "Enable WhatsApp Float Button" is Yes. Clicking it opens `https://wa.me/<number>?text=<message>`.
- Product page button: added to `product.info.additional.actions` (Hyva) and to `product.info.main` right after the add-to-cart form (Luma, block `whatsapp.product.button`) on `catalog_product_view`, so it shows for every product type, with or without options. On Hyva themes the Luma block is removed through the `hyva_catalog_product_view` handle so the button is not rendered twice. The template renders only when the product page switch is Yes.
- Category banner: added to the `content` container before the product list on `catalog_category_view`; renders only when the category switch is Yes.

Colours and sizes (button background and text colour, floating button size, icon size, side offset, bottom offset) come from `etc/theme-config.json`. Panth Core turns that file into CSS variables on every page, and a theme can override those variables in its own CSS. See the user guide for the variable names.

Templates can be overridden in a theme under `Panth_WhatsApp/templates/`: `whatsapp-float.phtml`, `product/button.phtml` and `category/banner.phtml`.

## Developer Notes

- Module name: `Panth_WhatsApp`
- Composer package: `mage2kishan/module-whatsapp`
- PHP namespace: `Panth\WhatsApp`
- View models: `Panth\WhatsApp\ViewModel\FloatButton`, `Panth\WhatsApp\ViewModel\Product`, `Panth\WhatsApp\ViewModel\Category` (each exposes `getWhatsAppUrl()`)
- Config helper: `Panth\WhatsApp\Helper\Data`
- ACL resource for the configuration section: `Panth_WhatsApp::config`

## Uninstallation

```bash
bin/magento module:disable Panth_WhatsApp
composer remove mage2kishan/module-whatsapp
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

The module creates no database tables. Its configuration values remain in `core_config_data` until removed.

## Support

- Product page: [kishansavaliya.com/magento-2-whatsapp.html](https://kishansavaliya.com/magento-2-whatsapp.html)
- Contact: [kishansavaliya.com/contact](https://kishansavaliya.com/contact)
- Email: kishansavaliyakb@gmail.com
- Bug reports: [GitHub issues](https://github.com/mage2sk/module-whatsapp/issues)

## Documentation

- [USER_GUIDE.md](USER_GUIDE.md): installation, configuration of each element, colour customisation through `theme-config.json` and troubleshooting.

## License

Commercial software license. See [LICENSE.txt](LICENSE.txt) in this repository.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Links

- Website: [kishansavaliya.com](https://kishansavaliya.com)
- All extensions: [kishansavaliya.com/magento-extensions.html](https://kishansavaliya.com/magento-extensions.html)
- GitHub: [mage2sk/module-whatsapp](https://github.com/mage2sk/module-whatsapp)
- Packagist: [mage2kishan/module-whatsapp](https://packagist.org/packages/mage2kishan/module-whatsapp)
