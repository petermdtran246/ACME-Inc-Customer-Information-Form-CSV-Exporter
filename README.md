# ACME Inc — Customer Information Form & CSV Exporter
A PHP web application built for ACME Inc that collects customer profile information through a validated HTML5 form and converts each submission into a single, database-ready CSV (Comma-Separated Values) record.

## Overview

While awaiting final database deployment, this application captures 11 customer data fields across text, numeric, radio button, and checkbox inputs (`index.html`) and processes them on the server (`process.php`) into a properly escaped CSV line ready for future database import.

## Features

- **11-Field HTML5 Customer Form:** Collects personal details, mailing address, account type, and product interests using semantic `<fieldset>` groups and HTML5 validation attributes (`required`, `type="email"`, `min`/`max`, and 5-digit `pattern` matching for zip codes).
- **Decoupled CSV Builder Function:** Implements `buildCsvRecord()` with 11 explicit parameters—keeping all `$_POST` extraction, trimming, and validation completely outside the conversion function.
- **Safe CSV Formatting & Delimiter Handling:** Automatically escapes embedded double quotes (`""`) and wraps fields containing commas, quotes, or line breaks in double quotes (`"..."`). Multiple checkbox selections are joined with semicolons (`; `) so they remain inside a single CSV column (defaulting to `None` if left unchecked).
- **Server-Side Validation & Access Control:** Redirects direct `GET` requests back to `index.html`, verifies all required fields on the server, and validates email syntax with `filter_var(..., FILTER_VALIDATE_EMAIL)`.
- **XSS Protection & Responsive Corporate UI:** Escapes all rendered user data with `htmlspecialchars()` and shares a responsive stylesheet (`style.css`) across both the form and result/error views.

## CSV Column Structure (11 Fields)

| Column | Field Name | Input Type | Notes |
| :--- | :--- | :--- | :--- |
| 1 | `firstName` | Text | Trimmed string |
| 2 | `lastName` | Text | Trimmed string |
| 3 | `email` | Email | Validated on client and server |
| 4 | `phone` | Tel | Trimmed string |
| 5 | `age` | Number | Integer between `18` and `120` |
| 6 | `street` | Text | Quoted automatically if commas are present |
| 7 | `city` | Text | Trimmed string |
| 8 | `state` | Select | Two-letter US state abbreviation |
| 9 | `zipCode` | Text (Numeric) | 5-digit string to preserve leading zeros (e.g., `02108`) |
| 10 | `accountType` | Radio | `Individual`, `Business`, or `Non-Profit` |
| 11 | `interests` | Checkboxes | Semicolon-separated list or `None` |

## Example Input & Output

**Form Submission:**
- **Name:** John Doe
- **Email:** `john.doe@example.com`
- **Phone:** `312-555-0199`
- **Age:** `28`
- **Address:** `100 Main St, Apt 4B`, Chicago, IL `60601`
- **Account Type:** Business
- **Product Interests:** Hardware, Cloud Services

**Generated CSV Line:**
```text
John,Doe,john.doe@example.com,312-555-0199,28,"100 Main St, Apt 4B",Chicago,IL,60601,Business,Hardware; Cloud Services
```

## Project Structure

```text
project08/
├── index.html   # Validated HTML5 customer registration form
├── process.php  # Server-side validation and decoupled CSV conversion script
├── style.css    # Shared responsive corporate styling for form and output views
└── README.md    # Project documentation
```

## How to Run Locally

1. Place the `project08` folder inside your local web server's document root (for example, `Applications/MAMP/htdocs/project08/`).
2. Start Apache in MAMP (or run `php -S localhost:8000` inside the project directory).
3. Open your browser and navigate to `http://localhost:8888/project08/index.html` (or `http://localhost:8000/index.html`).

## Author

Peter Tran (2026)
