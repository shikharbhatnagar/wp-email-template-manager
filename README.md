# WP Email Template Manager

A simple and easy-to-use WordPress plugin to create, edit, preview, categorize, and manage email templates using a rich HTML editor. Ideal for developers, marketers, or site owners who want full control over email content.

## 🛠 Features

- Create custom HTML email templates using TinyMCE/WordPress editor
- Edit and categorize templates (e.g., Welcome, Promo, Notification)
- Live preview with inline styling
- Use placeholders like `{{name}}`, `{{email}}`, `{{date}}`, etc.
- Delete templates
- Supports dynamic placeholder replacement before sending

## 🔧 Placeholders

You can include the following placeholders in the subject or body:

- `{{name}}`
- `{{email}}`
- `{{date}}`
- `{{custom_field}}` (add your own via code)

These will be replaced dynamically when sending actual emails.

## 📦 Installation

1. Download or clone this repository
2. Place the folder inside `/wp-content/plugins/`
3. Activate the plugin from **WordPress Admin > Plugins**

## 🚀 Usage

1. Go to **Email Templates** in your WordPress admin sidebar
2. Add, edit, or delete templates
3. Click "Send Test Email" to preview email output
4. Use the exported templates or send via custom hooks in your theme/plugin

## 🧪 Sending Emails via Code

You can send an email using a saved template like this:

```php[
Endpoint URL:  wp-json/etm/v1/send
e.g. https://your-domain/wp-json/etm/v1/send

Method: POST
Raw Json payload:
{
    "to": "bhatnagar.shikhar@gmail.com",
    "subject": "This i ssubject line",
    "template": "Hello there, welcome aboard"
}
