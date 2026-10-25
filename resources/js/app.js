import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

function setAvailabilityMessage(message, type = 'secondary') {
    const box = $('#availability');
    if (!box.length) return;

    box.removeClass('alert-secondary alert-success alert-danger alert-info')
        .addClass(`alert-${type}`)
        .text(message);
}

$(document).on('change', '#course_id', function () {
    const id = this.value;
    const template = $(this).data('availability-template');

    if (!id) {
        setAvailabilityMessage('Pilih kursus untuk melihat ketersediaan sisa kursi.');
        return;
    }

    setAvailabilityMessage('Memeriksa ketersediaan kursi...');

    const url = template ? String(template).replace('__COURSE__', id) : `/courses/${id}/availability`;

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success(data) {
            const courseName = data.course || 'Kursus';
            const remaining = data.remaining !== undefined ? data.remaining : 0;
            const suffix = data.status && data.status !== 'open' ? ` (Status: ${data.status})` : '';
            setAvailabilityMessage(`${remaining} kursi tersisa untuk ${courseName}.${suffix}`, 'success');
        },
        error() {
            setAvailabilityMessage('Gagal memuat ketersediaan kursus. Silakan coba lagi.', 'danger');
        }
    });
});