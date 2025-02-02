<div id="audit-trail-modal" class="audit-trail-modal">
    <div class="audit-trail-modal-content">
        <div class="audit-trail-modal-header">
            <span class="audit-trail-close audit-trail-modal-close">&times;</span>
            <h3>Audit Trail Details</h3>
        </div>
        <div class="audit-trail-modal-body"></div>
        <div class="audit-trail-modal-footer text-right">
            <button type="button" class="audit-trail-modal-close text-red">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get the modal
    var modal = document.getElementById("audit-trail-modal");
    var closeButtons = document.querySelectorAll(".audit-trail-modal-close");

    // Get all buttons with the class 'audit-trail-modal-button'
    var buttons = document.querySelectorAll(".audit-trail-modal-button");

    // Loop through all buttons and attach the click event
    buttons.forEach(function (btn) {
        btn.onclick = function () {
            modal.style.display = "flex";
            var data = JSON.parse(this.getAttribute("data-item"));
            var body = modal.querySelector(".audit-trail-modal-body");

            var logHtml = `
                <table>
                    <tr>
                        <th>Type</th>
                        <td>${data.type}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>${data.message}</td>
                    </tr>
                    <tr>
                        <th>Model Type</th>
                        <td>${data.model_type}</td>
                    </tr>
                    <tr>
                        <th>Model ID</th>
                        <td>${data.model_id}</td>
                    </tr>
                    <tr>
                        <th>Creator Type</th>
                        <td>${data.creator_type}</td>
                    </tr>
                    <tr>
                        <th>Creator ID</th>
                        <td>${data.creator_id}</td>
                    </tr>
                    <tr>
                        <th>Batch ID</th>
                        <td>${data.batch_id}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>${data.status}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>${data.created_at}</td>
                    </tr>
                </table>
            `;

            // Generate HTML for data details
            var dataHtml = `
                <h3>Data Changes</h3>
                <table>
                    <tr>
                        <th>Field</th>
                        <th>New Value</th>
                        <th>Old Value</th>
                    </tr>
            `;

            var attributes = JSON.parse(data.data);
            for (var key in attributes) {
                dataHtml += `
                    <tr>
                        <td>${key}</td>
                        <td>${attributes[key]?.new ?? ''}</td>
                        <td>${attributes[key]?.old ?? ''}</td>
                    </tr>
                `;
            }

            dataHtml += `</table>`;

            // Generate HTML for agent details
            var agent = JSON.parse(data.agent);
            var agentHtml = `
                <h3>Agent Details</h3>
                <table>
                    <tr>
                        <th>IP</th>
                        <td>${agent.ip}</td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td>${agent["user-agent"]}</td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td>${agent.url}</td>
                    </tr>
                </table>
            `;

            // Combine and insert into modal body
            body.innerHTML = logHtml + dataHtml + agentHtml;
        };
    });

    // Close the modal when the close button is clicked
    closeButtons.forEach(function (btn) {
        btn.onclick = function () {
            modal.style.display = "none";
        };
    });

    // Close the modal when clicking outside of it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
</script>
