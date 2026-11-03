// universal delete event handler (used in multiple pages)
document.addEventListener('click', (e) => {
    let btn = e.target.closest('.entry-delete-btn, .report-delete-btn');
    if (!btn) return;
    
    e.preventDefault();

    let deleteUrl = btn.getAttribute('data-url');
    if (!deleteUrl) {
        let itemId = btn.dataset.id || (btn.parentNode && btn.parentNode.id);
        let urlInput = document.querySelector(`#deleteUrl${itemId}`);
        if (urlInput) {
            deleteUrl = urlInput.value;
        }
    }

    if (!deleteUrl) {
        console.error('Delete URL not found!');
        return;
    }

    let csrfTokenObj = document.querySelector('meta[name="csrf-token"]');
    let csrfToken = csrfTokenObj ? csrfTokenObj.content : '';

    let deletedBtnTextObj = document.querySelector('#deletedBtnText');
    let deletedBtnText = deletedBtnTextObj ? deletedBtnTextObj.value : 'Yes, delete it!';
    
    let deletedTitleObj = document.querySelector('#deletedTitle');
    let deletedTitle = deletedTitleObj ? deletedTitleObj.value : 'Deleted!';
    
    let deletedMsgObj = document.querySelector('#deletedMsg');
    let deletedMsg = deletedMsgObj ? deletedMsgObj.value : 'Successfully deleted.';

    let redirectUrl = window.location.href;
    try {
        let redirectUrlObj = document.querySelector('#closedRedirectUrl');
        if (redirectUrlObj) {
            redirectUrl = redirectUrlObj.value;
        }
    } catch (error) {
        console.log(error);
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4338CA',
        cancelButtonColor: '#d33',
        confirmButtonText: deletedBtnText
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
            .then(async (response) => {
                if (response.ok) {
                    Swal.fire(
                        deletedTitle,
                        deletedMsg,
                        'success'
                    ).then(() => {
                        window.location.href = redirectUrl;
                    });
                } else {
                    let errMsg = 'Request was unsuccessful.';
                    try {
                        let errData = await response.json();
                        errMsg = errData.message || errMsg;
                    } catch(e) {}
                    
                    Swal.fire(
                        'Failed (HTTP ' + response.status + ')',
                        errMsg + ' Please try again later.',
                        'error'
                    ).then(() => {
                        window.location.reload();
                    });
                }
            }).catch((error) => {
                Swal.fire(
                    'Error',
                    'Network error or JS crash: ' + error,
                    'error'
                );
            });
        }
    });

});
