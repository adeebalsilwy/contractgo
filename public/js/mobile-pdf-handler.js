/**
 * Mobile PDF Printing Helper
 * This script helps handle PDF printing on mobile devices, especially iOS
 */

class MobilePdfHandler {
    constructor() {
        this.isMobile = this.detectMobile();
        this.isIOS = this.detectIOS();
        this.setupEventListeners();
    }

    detectMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    detectIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    setupEventListeners() {
        // Handle PDF download/print buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-pdf-print]') || e.target.closest('[data-pdf-print]')) {
                e.preventDefault();
                const pdfUrl = e.target.href || e.target.closest('[data-pdf-print]').href;
                this.handlePdfAction(pdfUrl);
            }
        });
    }

    handlePdfAction(pdfUrl) {
        if (this.isIOS) {
            // iOS-specific handling
            this.handleIOSPdf(pdfUrl);
        } else if (this.isMobile) {
            // Other mobile devices
            this.handleMobilePdf(pdfUrl);
        } else {
            // Desktop
            window.open(pdfUrl, '_blank');
        }
    }

    handleIOSPdf(pdfUrl) {
        // For iOS, create a temporary link with proper attributes
        const link = document.createElement('a');
        link.href = pdfUrl;
        link.download = '';
        link.target = '_blank';
        
        // Add iOS-specific attributes
        link.setAttribute('rel', 'noopener noreferrer');
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show user feedback
        this.showNotification('Opening PDF in new tab...');
    }

    handleMobilePdf(pdfUrl) {
        // For Android and other mobile devices
        const link = document.createElement('a');
        link.href = pdfUrl;
        link.target = '_blank';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        this.showNotification('Opening PDF...');
    }

    showNotification(message) {
        // Create a simple notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            z-index: 10000;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }

    // Method to test PDF generation
    testPdfGeneration(contractId) {
        const testUrl = `/test-pdf/${contractId}`;
        this.handlePdfAction(testUrl);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.mobilePdfHandler = new MobilePdfHandler();
    
    // Add console helper for testing
    console.log('Mobile PDF Handler initialized');
    console.log('Use window.mobilePdfHandler.testPdfGeneration(contractId) to test');
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MobilePdfHandler;
}