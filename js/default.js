const deletePost = () => {
    if (confirm('削除しますか？')) {
        document.getElementById('form-delete').submit()
    }
}