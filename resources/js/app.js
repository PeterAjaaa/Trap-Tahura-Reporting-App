import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: false,
    encrypted: false,
});

document.addEventListener('DOMContentLoaded', () => {
    function enterFullscreen() {
        const element = document.documentElement;

        // Multiple browser-specific methods
        const fullscreenMethods = [
            'requestFullscreen',
            'webkitRequestFullscreen',
            'mozRequestFullScreen',
            'msRequestFullscreen'
        ];

        for (let method of fullscreenMethods) {
            if (element[method]) {
                try {
                    element[method]();
                    console.log(`Fullscreen entered using ${method}`);
                    return true;
                } catch (error) {
                    console.warn(`Fullscreen method ${method} failed:`, error);
                }
            }
        }

        return false;
    }

    // Comprehensive Fullscreen Strategy
    function initFullscreenStrategy() {
        // Multiple trigger points
        function tryFullscreen() {
            if (!document.fullscreenElement) {
                enterFullscreen();
            }
        }

        // Immediate attempts
        tryFullscreen();

        // Delayed attempts
        const delays = [100, 500, 1000];
        delays.forEach(delay => {
            setTimeout(tryFullscreen, delay);
        });

        // Event-based triggers
        window.addEventListener('load', tryFullscreen);
        document.addEventListener('DOMContentLoaded', tryFullscreen);
    }

    // Initialize on script load
    document.addEventListener('DOMContentLoaded', initFullscreenStrategy);

    // Fallback: Force on first user interaction
    document.addEventListener('click', function firstInteractionHandler() {
        enterFullscreen();
        document.removeEventListener('click', firstInteractionHandler);
    });

    try {
        window.Echo.channel('reports')
            .listen('ReportClosed', (event) => {
                const row = document.querySelector(`tr[data-id="${event.id}"]`);
                if (row) {
                    // Remove all existing styling classes
                    row.classList.remove(
                        'priority-high',
                        'priority-medium',
                        'priority-low',
                        'table-danger',
                        'bg-danger',
                        'bg-warning',
                        'bg-info',
                        'text-white',
                        'text-dark'
                    );

                    // Add neutral closed styling to row
                    row.classList.add('text-muted', 'bg-light');

                    // Neutralize pill styles
                    const pillElements = row.querySelectorAll('.badge, .pill, .status-pill');
                    pillElements.forEach(pill => {
                        // Remove all existing color classes
                        pill.classList.remove(
                            'bg-primary',
                            'bg-secondary',
                            'bg-success',
                            'bg-danger',
                            'bg-warning',
                            'bg-info',
                            'bg-light',
                            'bg-dark',
                            'text-primary',
                            'text-secondary',
                            'text-success',
                            'text-danger',
                            'text-warning',
                            'text-info',
                            'text-light',
                            'text-dark'
                        );

                        // Add neutral styling
                        pill.classList.add('bg-secondary', 'text-white');
                    });

                    // Update status cell and row styling
                    const statusCell = row.querySelector('.status-cell');
                    if (statusCell) {
                        statusCell.textContent = 'Closed';
                    }

                    // Update form to disable existing elements
                    const statusForm = row.querySelector('form');
                    if (statusForm) {
                        // Disable the select and change button
                        const selectElement = statusForm.querySelector('select');
                        const buttonElement = statusForm.querySelector('button');

                        if (selectElement) {
                            selectElement.disabled = true;
                            selectElement.selectedIndex = 4;
                        }

                        if (buttonElement) {
                            buttonElement.disabled = true;
                            buttonElement.classList.remove('btn-success');
                            buttonElement.classList.add('btn-secondary');
                            buttonElement.textContent = 'Ditutup';
                        }
                    }

                    // Reorganize table rows
                    const tbody = row.closest('tbody');
                    if (tbody) {
                        // Get all rows
                        const allRows = Array.from(tbody.querySelectorAll('tr'));

                        // Completely remove all existing separators
                        tbody.querySelectorAll('tr.separator-row').forEach(sep => sep.remove());

                        // Separate rows
                        const activeRows = [];
                        const closedRows = [];

                        allRows.forEach(r => {
                            const statusCell = r.querySelector('.status-cell');
                            if (statusCell) {
                                if (statusCell.textContent === 'Closed') {
                                    closedRows.push(r);
                                } else {
                                    activeRows.push(r);
                                }
                            }
                        });

                        // Clear tbody
                        tbody.innerHTML = '';

                        // Reinsert active rows
                        activeRows.forEach(row => tbody.appendChild(row));

                        // Add single separator if closed rows exist
                        if (closedRows.length > 0) {
                            const separatorRow = document.createElement('tr');
                            separatorRow.classList.add('separator-row');
                            separatorRow.innerHTML = `
                            <td colspan="8" class="text-center bg-secondary text-white">
                                Laporan Ditutup
                            </td>
                        `;
                            tbody.appendChild(separatorRow);
                        }

                        // Reinsert closed rows
                        closedRows.forEach(row => tbody.appendChild(row));

                    }
                }
                const insertClosedRows = (tbody) => {
                    // Find all closed rows
                    const closedRows = Array.from(tbody.querySelectorAll('tr')).filter(row =>
                        row.querySelector('.status-cell')?.textContent === 'Closed'
                    );

                    // Prioritization logic for closed rows
                    const prioritySort = (a, b) => {
                        const getPriority = (row) => {
                            const badge = row.querySelector('.priority-cell .badge');
                            return badge ? parseInt(badge.textContent) : 0;
                        };
                        return getPriority(b) - getPriority(a); // Descending order
                    };

                    // Sort closed rows by priority
                    const sortedClosedRows = closedRows.sort(prioritySort);

                    // Find or create separator row
                    let separatorRow = tbody.querySelector('tr.separator-row');
                    if (!separatorRow && sortedClosedRows.length > 0) {
                        separatorRow = document.createElement('tr');
                        separatorRow.classList.add('separator-row');
                        separatorRow.innerHTML = `
            <td colspan="8" class="text-center bg-secondary text-white">
                Laporan Ditutup
            </td>
        `;
                    }

                    // Remove existing closed rows and separator
                    closedRows.forEach(row => row.remove());
                    if (separatorRow && separatorRow.parentElement) {
                        separatorRow.remove();
                    }

                    // Reinsert separator and sorted closed rows
                    if (sortedClosedRows.length > 0) {
                        // Find the last active row
                        const activeRows = Array.from(tbody.querySelectorAll('tr'))
                            .filter(row => row.querySelector('.status-cell')?.textContent !== 'Closed');

                        const lastActiveRow = activeRows[activeRows.length - 1];

                        if (lastActiveRow) {
                            // Insert separator after last active row
                            tbody.insertBefore(separatorRow, lastActiveRow.nextSibling);

                            // Insert sorted closed rows after separator
                            sortedClosedRows.forEach(row => {
                                tbody.appendChild(row);
                            });
                        } else {
                            // If no active rows, just append separator and closed rows
                            tbody.appendChild(separatorRow);
                            sortedClosedRows.forEach(row => {
                                tbody.appendChild(row);
                            });
                        }
                    }
                };
                // Call the function to prioritize closed rows
                const tbody = row.closest('tbody');
                if (tbody) {
                    insertClosedRows(tbody);
                }
            })

            .listen('ReportCreated', (event) => {
                // Corrected variable name
                const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value;

                // Check if the current user is the one assigned to the report
                if (!currentUserId || !event.admin_id || event.admin_id.toString() !== currentUserId) {
                    console.log('Report not assigned to current user');
                    return;
                }

                const tableBody = document.querySelector('#reports-table tbody');
                if (!tableBody) {
                    console.error('Reports table body not found');
                    return;
                }

                // Create new row
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', event.id);
                newRow.classList.toggle('table-danger', event.priority === 5);

                // Construct row HTML
                newRow.innerHTML = `
        <td>${event.id}</td>
        <td class="title-cell">${event.title}</td>
        <td class="type-cell">${event.type}</td>
        <td class="priority-cell">
            <span class="badge bg-${event.priority === 5 ? 'danger' : (event.priority >= 3 ? 'warning' : 'success')}">
                ${event.priority}
            </span>
        </td>
        <td class="description-cell">${event.description}</td>
        <td class="status-cell">${event.status}</td>
        <td>
            <form action="/reports/${event.id}/status" method="POST" class="d-flex align-items-center">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <select name="status" class="form-select form-select-sm me-2">
                    <option value="Pending" ${event.status === 'Pending' ? 'selected' : ''}>Pending</option>
                    <option value="Assigned" ${event.status === 'Assigned' ? 'selected' : ''}>Assigned</option>
                    <option value="In Progress" ${event.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
                    <option value="Resolved" ${event.status === 'Resolved' ? 'selected' : ''}>Resolved</option>
                    <option value="Closed" ${event.status === 'Closed' ? 'selected' : ''}>Closed</option>
                </select>
                <button type="submit" class="btn btn-success btn-sm">Update</button>
            </form>
        </td>
        <td class="photo-cell">
            ${event.photo ? `<img src="${event.photo}" alt="Foto Laporan" class="img-fluid img-thumbnail vw-25">` : 'Foto Laporan Tidak Tersedia'}
        </td>
    `;

                // Insertion logic
                const insertRow = (newRow) => {
                    const tableBody = document.querySelector('#reports-table tbody');
                    const closedRows = Array.from(tableBody.querySelectorAll('tr')).filter(row =>
                        row.querySelector('.status-cell')?.textContent === 'Closed'
                    );
                    const separatorRow = closedRows.length > 0 ? closedRows[0].previousElementSibling : null;

                    // Find all active rows before the separator
                    const activeRows = Array.from(tableBody.querySelectorAll('tr'))
                        .filter(row => {
                            const statusCell = row.querySelector('.status-cell');
                            return statusCell && statusCell.textContent !== 'Closed' && row !== separatorRow;
                        });

                    // Prioritization logic
                    const prioritySort = (a, b) => {
                        const priorityA = parseInt(a.querySelector('.priority-cell .badge')?.textContent || '0');
                        const priorityB = parseInt(b.querySelector('.priority-cell .badge')?.textContent || '0');
                        return priorityB - priorityA; // Descending order
                    };

                    // Combine existing active rows with the new row
                    const allActiveRows = [...activeRows, newRow];
                    const sortedActiveRows = allActiveRows.sort(prioritySort);

                    // Remove all active rows from the table
                    activeRows.forEach(row => row.remove());

                    // Reinsert sorted rows before the separator
                    sortedActiveRows.forEach(row => {
                        if (separatorRow) {
                            tableBody.insertBefore(row, separatorRow);
                        } else {
                            tableBody.appendChild(row);
                        }
                    });
                };
                // Perform insertion
                insertRow(newRow);
            });

    } catch (error) {
        console.error('Error setting up real-time listener:', error);
    }
});
