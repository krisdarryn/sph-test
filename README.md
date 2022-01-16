## QR Link generator

### Demo Site
URL: https://dev-kris-demo.pantheonsite.io

Credentials: **krisdarryn** / **JJX7aKQYSiuzXVJ**

### Installation guide:
1. Install the third-party QR code generator library in the app's root directory

    a. Run: *composer require bacon/bacon-qr-code*

2. Put the custom module under your custom module directory; then
3. Enable the module via the Admin UI or Drush: *drush en qr_link_generator -y*
4. Place the custom block (QR Link Generator Block) in the Sidebar right region.

That should display the QR code in each Product detail page.
