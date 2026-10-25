import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

function setAvailabilityMessage(htmlMessage, type = 'secondary', isUpdating = false) {
    const box = $('#availability');
    if (!box.length) return;

    if (isUpdating) {
        box.addClass('is-updating');
    } else {
        box.removeClass('is-updating');
    }

    box.fadeOut(120, function () {
        box.removeClass('alert-secondary alert-success alert-danger alert-info')
            .addClass(`alert-${type}`)
            .html(htmlMessage)
            .fadeIn(180);
    });
}

$(document).ready(function () {
    // Ajax availability check
    $(document).on('change', '#course_id', function () {
        const id = this.value;
        const template = $(this).data('availability-template');

        if (!id) {
            setAvailabilityMessage('Pilih kursus untuk melihat sisa kursi melalui Ajax.', 'secondary');
            return;
        }

        setAvailabilityMessage('<em>Memeriksa ketersediaan kursi...</em>', 'secondary', true);

        const url = template ? String(template).replace('__COURSE__', encodeURIComponent(id)) : `/courses/${id}/availability`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success(data) {
                const courseName = data.course || 'Kursus';
                const remaining = data.remaining !== undefined ? data.remaining : 0;
                const statusText = data.status && data.status !== 'open' ? ` (Status: ${data.status})` : '';
                const msg = `<strong>${courseName}</strong> — Tersisa <strong>${remaining} kursi</strong>${statusText}.`;
                setAvailabilityMessage(msg, 'success');
            },
            error() {
                setAvailabilityMessage('<strong>Gagal:</strong> Tidak dapat memuat data ketersediaan kursi.', 'danger');
            }
        });
    });

    // Scroll reveal observer
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
    } else {
        $('.reveal-on-scroll').addClass('revealed');
    }
});