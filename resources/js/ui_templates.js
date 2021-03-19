youtube_embed_template = function (params) {
    if (!params) params = {};
    if (!params.width) params.width = '100%';
    if (!params.height) params.height = '300';
    if (!params.video_id) return 'no video';
    return `<div style="position: relative; padding-bottom: 56.25%; height: 0;">
<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" width="${params.width}" height="${params.height}" 
src="https://www.youtube.com/embed/${params.video_id}" 
frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
`}