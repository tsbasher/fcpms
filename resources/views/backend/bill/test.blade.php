<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mixed Print Orientation (Default Landscape)</title>
    <style>
        /* Define layouts globally */
        @page portrait-layout {
            size: portrait;
            margin: 15mm; 
        }

        @page landscape-layout {
            size: landscape;
            margin: 15mm; 
        }

        @media print {
            /* 1. Set the baseline document flow to LANDSCAPE */
            body {
                page: landscape-layout;
            }

            /* 2. Target the switchers. 
               The page property natively handles the break without duplicating page counts. */
            .switch-to-portrait {
                page: portrait-layout;
                height: 0;
                margin: 0;
                padding: 0;
                display: block;
            }

            .switch-to-landscape {
                page: landscape-layout;
                height: 0;
                margin: 0;
                padding: 0;
                display: block;
            }

            /* 3. Keep tables flowing naturally across breaks */
            table {
                break-inside: auto;
            }
            
            tr {
                break-inside: avoid;
            }
        }

        /* Your table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            font-size: 10px;
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
<body>

    <!-- Start in default Landscape (Matches body layout) -->
    <div class="content-section">
        <table>
            <tr><th>Initial Landscape Header</th></tr>
            <tr><td>This content will print in landscape by default.</td></tr>
        </table>
    </div>

    <!-- SWITCH TO PORTRAIT -->
    <div class="switch-to-portrait"></div>

    <div class="content-section">
        <table>
            <tr><th>Portrait Header</th></tr>
            <tr><td>This content switches seamlessly to portrait mode.</td></tr>
        </table>
    </div>

    <!-- SWITCH BACK TO LANDSCAPE -->
    <div class="switch-to-landscape"></div>

    <div class="content-section">
        <table>
            <tr><th>Final Landscape Header</th></tr>
            <tr><td>This content returns safely back to landscape layout.</td></tr>
        </table>
    </div>

</body>
</html>
