import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
});

document.addEventListener('DOMContentLoaded', () => {
    try {
        window.Echo.channel('reports')
            .listen('ReportCreated', (event) => {
                console.log('Report Created Event Received:', event);

                // Validate required data
                if (!event || !event.id) {
                    console.error('Invalid report data', event);
                    return;
                }

                // Find the table body
                const tableBody = document.querySelector('#reports-table tbody');
                if (!tableBody) {
                    console.error('Reports table body not found');
                    return;
                }

                // Create new row
                const newRow = document.createElement('tr');

                // Safely set data-id
                if (event.id) {
                    newRow.setAttribute('data-id', event.id);
                }

                // Safely add danger class
                if (event.priority === 5) {
                    newRow.classList.add('table-danger');
                }

                // Determine priority badge class
                const getPriorityBadgeClass = (priority) => {
                    if (priority === 5) return 'danger';
                    if (priority >= 3) return 'warning';
                    return 'success';
                };

                // Safely handle potentially undefined values
                const safeValue = (value, defaultValue = 'N/A') =>
                    value !== undefined && value !== null ? value : defaultValue;

                // Construct row HTML with safe value handling
                newRow.innerHTML = `
                    <td>${safeValue(event.id)}</td>
                    <td class="title-cell">${safeValue(event.title)}</td>
                    <td class="type-cell">${safeValue(event.type)}</td>
                    <td class="priority-cell">
                        <span class="badge bg-${getPriorityBadgeClass(event.priority)}">
                            ${safeValue(event.priority)}
                        </span>
                    </td>
                    <td class="description-cell">${safeValue(event.description)}</td>
                    <td class="status-cell">${safeValue(event.status)}</td>
                    <td>
                        <form action="/reports/${safeValue(event.id)}/status" method="POST" class="d-flex align-items-center">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="PATCH">
                            <select name="status" class="form-select form-select-sm me-2">
                                ${['Pending', 'Assigned', 'In Progress', 'Resolved', 'Closed']
                        .map(status => `
                                        <option value="${status}" ${status === event.status ? 'selected' : ''}>
                                            ${status}
                                        </option>
                                    `).join('')}
                            </select>
                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </form>
                    </td>
                    <td class="photo-cell">
                        ${event.photo
                        ? `<img src="${window.location.origin}/report/photo/${safeValue(event.id)}" alt="Foto Laporan" class="img-fluid img-thumbnail vw-25">`
                        : 'No Photo Available'}
                    </td>
                `;

                // Sort and insert the new row
                const insertRow = (newRow) => {
                    const rows = Array.from(tableBody.querySelectorAll('tr'));

                    // If no rows, simply append
                    if (rows.length === 0) {
                        tableBody.appendChild(newRow);
                        return;
                    }

                    // Find the correct position based on priority
                    const insertIndex = rows.findIndex(row => {
                        const existingPriority = parseInt(row.querySelector('.priority-cell .badge')?.textContent || '1');
                        return event.priority > existingPriority;
                    });

                    // Insert at the found index or at the end
                    if (insertIndex === -1) {
                        tableBody.appendChild(newRow);
                    } else {
                        tableBody.insertBefore(newRow, rows[insertIndex]);
                    }
                };

                // Perform the sorted insertion
                insertRow(newRow);
            })
            .error((error) => {
                console.error('Pusher Channel Error:', error);
            });

        // Connection status logging
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('Pusher Connection Established');
        });

        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.error('Pusher Connection Lost');
        });

    } catch (error) {
        console.error('Error setting up real-time listener:', error);
    }
});
