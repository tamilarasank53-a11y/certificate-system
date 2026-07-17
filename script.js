// Handle form submission
document.getElementById('certificateForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const name = document.getElementById('name').value.trim();
    const rollno = document.getElementById('rollno').value.trim();

    const messageDiv = document.getElementById('message');
    const downloadDiv = document.getElementById('downloadLink');

    // Clear previous messages
    messageDiv.innerHTML = '';
    messageDiv.className = 'message';
    downloadDiv.innerHTML = '';
    downloadDiv.className = 'download-link';

    // Show loading message
    messageDiv.textContent = 'Processing your request...';
    messageDiv.className = 'message loading';

    try {
        // Send request to backend (PHP or Python)
        const response = await fetch('backend/verify_student.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                name: name,
                rollno: rollno
            })
        });

        const data = await response.json();

        if (data.success) {
            messageDiv.textContent = '✓ ' + data.message;
            messageDiv.className = 'message success';

            // Show download link
            downloadDiv.innerHTML = `<a href="${data.certificate_link}" download>📥 Download Certificate</a>`;
            downloadDiv.className = 'download-link';

            // Clear form
            document.getElementById('certificateForm').reset();

            // Show success for 5 seconds then auto-download
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = data.certificate_link;
                link.download = `Certificate_${name.replace(/\s+/g, '_')}.pdf`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, 1500);
        } else {
            messageDiv.textContent = '✗ ' + data.message;
            messageDiv.className = 'message error';
        }
    } catch (error) {
        messageDiv.textContent = '✗ Error: ' + error.message;
        messageDiv.className = 'message error';
        console.error('Error:', error);
    }
});
