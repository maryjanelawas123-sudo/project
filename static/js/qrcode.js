/**
 * QR Code Generator for MCC Lost & Found
 */

class QRCodeGenerator {
    constructor() {
        this.qrCode = null;
        this.canvas = null;
        this.ctx = null;
    }
    
    /**
     * Generate QR code for URL
     */
    generateForURL(url, containerId, size = 200) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Clear previous QR code
        container.innerHTML = '';
        
        // Create canvas
        this.canvas = document.createElement('canvas');
        this.canvas.width = size;
        this.canvas.height = size;
        this.ctx = this.canvas.getContext('2d');
        
        container.appendChild(this.canvas);
        
        // Generate QR code
        this.qrCode = new QRCode({
            content: url,
            container: container,
            width: size,
            height: size,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
    
    /**
     * Generate QR code for item claim
     */
    generateForItem(itemId, containerId) {
        const url = `${APP_URL}/public/claim/${itemId}`;
        this.generateForURL(url, containerId);
    }
    
    /**
     * Generate QR code for app download
     */
    generateForApp(containerId) {
        const url = APP_URL;
        this.generateForURL(url, containerId, 250);
    }
    
    /**
     * Download QR code as PNG
     */
    download(filename = 'qrcode.png') {
        if (!this.canvas) return;
        
        const link = document.createElement('a');
        link.download = filename;
        link.href = this.canvas.toDataURL('image/png');
        link.click();
    }
    
    /**
     * Print QR code
     */
    print() {
        if (!this.canvas) return;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print QR Code</title>
                    <style>
                        body { text-align: center; padding: 20px; }
                        img { max-width: 100%; height: auto; }
                        .info { margin-top: 20px; font-family: Arial, sans-serif; }
                    </style>
                </head>
                <body>
                    <h2>MCC Lost & Found QR Code</h2>
                    <img src="${this.canvas.toDataURL('image/png')}" alt="QR Code">
                    <div class="info">
                        <p>Generated: ${new Date().toLocaleString()}</p>
                        <p>Scan this QR code to access the system</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
    
    /**
     * Generate multiple QR codes for items
     */
    generateBatch(itemIds, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = '';
        
        itemIds.forEach((itemId, index) => {
            const itemContainer = document.createElement('div');
            itemContainer.className = 'qr-item mb-3';
            itemContainer.innerHTML = `
                <div class="qr-code" id="qr-${itemId}"></div>
                <small>Item #${itemId}</small>
            `;
            container.appendChild(itemContainer);
            
            // Generate QR for this item
            setTimeout(() => {
                new QRCode({
                    content: `${APP_URL}/public/claim/${itemId}`,
                    container: document.getElementById(`qr-${itemId}`),
                    width: 120,
                    height: 120
                });
            }, index * 100);
        });
    }
}

// Initialize QR Code Generator
const qrGenerator = new QRCodeGenerator();

// Export to global scope
window.QRCodeGenerator = QRCodeGenerator;
window.qrGenerator = qrGenerator;

// QR Code Helper Functions
function generateItemQR(itemId, elementId) {
    qrGenerator.generateForItem(itemId, elementId);
}

function downloadQR(filename) {
    qrGenerator.download(filename);
}

function printQR() {
    qrGenerator.print();
}

// Auto-generate QR codes on page load
document.addEventListener('DOMContentLoaded', function() {
    // Generate app QR code if container exists
    const appQRContainer = document.getElementById('appQRCode');
    if (appQRContainer) {
        qrGenerator.generateForApp('appQRCode');
    }
    
    // Generate item QR codes
    document.querySelectorAll('[data-generate-qr]').forEach(element => {
        const itemId = element.dataset.itemId;
        const containerId = element.dataset.container || 'qrContainer';
        if (itemId) {
            generateItemQR(itemId, containerId);
        }
    });
});