document.addEventListener('DOMContentLoaded', function () {
    const postId = penci_live_visitor.post_id;
    const objectLabel = penci_live_visitor.object_label;
    const singularTemplate = penci_live_visitor.singular_text;
    const pluralTemplate = penci_live_visitor.plural_text;

    const visitorCounter = document.getElementById('visitor-count');

    if (!visitorCounter) {
        return;
    }

    function formatText(count) {
        if (count === 1) {
            return singularTemplate.replace('{object}', objectLabel);
        } else {
            return pluralTemplate.replace('{view}', count).replace('{object}', objectLabel);
        }
    }

    function pingServer() {
        fetch(`/wp-json/livevisitor/v1/update/${postId}`, { method: 'POST' }).catch(() => {});
    }

    function fetchCount() {
        fetch(`/wp-json/livevisitor/v1/count/${postId}`)
            .then(res => res.json())
            .then(data => {
                const count = parseInt(data.count, 10) || 0;

                if (count > 0) {
                    visitorCounter.classList.add('active');
                    visitorCounter.innerText = formatText(count);
                } else {
                    visitorCounter.classList.remove('active');
                    visitorCounter.innerText = '';
                }
            })
            .catch(() => {});
    }

    // Initial update & count fetch
    pingServer();
    fetchCount();

    // Update every 20 seconds
    setInterval(pingServer, 20000);
    setInterval(fetchCount, 10000);
});