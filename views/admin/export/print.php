<?php
// Disable header/footer for print
$hideNavbar = true;
$pageTitle = "Print Report";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - MCC Lost & Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        
        .print-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .print-header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .print-header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        
        .print-header .meta {
            font-size: 14px;
            color: #777;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px 10px;
        }
        
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        
        .no-print {
            display: none;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-header {
                page-break-after: avoid;
            }
            
            .table {
                page-break-inside: avoid;
            }
            
            .summary {
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls (Hidden when printing) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Report
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
        <hr style="margin: 20px 0;">
    </div>
    
    <!-- Report Header -->
    <div class="print-header">
        <h1>MCC LOST & FOUND SYSTEM</h1>
        <h2><?php echo strtoupper(str_replace('_', ' ', $table_name)); ?> REPORT</h2>
        <div class="meta">
            Generated on: <?php echo $now->format('F j, Y, g:i a'); ?><br>
            Total Records: <?php echo $row_count; ?>
        </div>
    </div>
    
    <!-- Report Data -->
    <?php if(!empty($data)): ?>
        <table class="table">
            <thead>
                <tr>
                    <?php foreach($columns as $column): ?>
                        <th><?php echo strtoupper(str_replace('_', ' ', $column)); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr>
                        <?php foreach($columns as $column): ?>
                            <td>
                                <?php 
                                $value = $row[$column] ?? '';
                                if (is_string($value) && strtotime($value)) {
                                    echo date('m/d/Y', strtotime($value));
                                } else {
                                    echo htmlspecialchars($value);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 5px;">
            <h3>No data available for export</h3>
            <p>There are no records to display.</p>
        </div>
    <?php endif; ?>
    
    <!-- Summary -->
    <div class="summary">
        <h3>Report Summary</h3>
        <p><strong>Table:</strong> <?php echo $table_name; ?></p>
        <p><strong>Total Records:</strong> <?php echo $row_count; ?></p>
        <p><strong>Generated By:</strong> <?php echo $_SESSION['admin_name'] ?? 'System Administrator'; ?></p>
        <p><strong>Generated On:</strong> <?php echo $now->format('F j, Y, g:i a'); ?></p>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>MCC Lost & Found System - Confidential Report</p>
        <p>© <?php echo date('Y'); ?> MCC. All rights reserved.</p>
        <p>Page generated automatically by the system.</p>
    </div>
    
    <script>
        // Auto-print if specified
        if (window.location.search.includes('autoprint')) {
            window.print();
        }
        
        // Close window after printing
        window.onafterprint = function() {
            if (window.location.search.includes('autoclose')) {
                window.close();
            }
        };
    </script>
</body>
</html>