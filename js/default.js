const deletePost = () => {
    if (confirm('削除しますか？')) {
        document.getElementById('form-delete').submit()
    }
}

const validate = (event) => {
    const elements = document.getElementsByName('message')
    const messageElement = elements[0];
    if (!messageElement.value) {
        messageElement.classList.add(['input-error'])
        document.getElementById('error-message').innerHTML = 'メッセージを入力してください'
        event.preventDefault()
    }
}