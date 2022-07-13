<script>
    import { currentUser, viewedProject, modalControl } from '../../../store';
    
    let newPostTitle = '';
    let newPostContent = '';

    const uploadPost = async() => {
        try {
            const data = new FormData();
            data.append('title', newPostTitle);
            data.append('contents', newPostContent);
            data.append('authorId', $currentUser.idUser);
            data.append('projectId', $viewedProject.idProject);
            const res = await fetch('/api/posts', {
                method: 'POST',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Post submitted');
                const json = await res.json();
                viewedProject.update(value => {
                    value.posts = [json[0], ...value.posts];
                    return value;
                });
                currentUser.update(value => {
                    value.posts = [json[0], ...value.posts];
                    return value;
                });
                newPostTitle = '';
                newPostContent = '';
            } else {
                modalControl.show('error', 'Could not submit the post');
            }
        } catch(e) {
            modalControl.show('error', 'Could not submit the post');
            console.log(e);
        }
    }
</script>

<div class="new-post-container general-container">
    <form class="new-post-form" on:submit|preventDefault={uploadPost}>
        <input  required type="text" name="title" class="new-post-title" placeholder="Title" bind:value={newPostTitle} />
        <!-- <input type="textarea" name="content" class="new-post-content" placeholder="Content"> -->
        <textarea  required name="content" id="" cols="30" rows="3" placeholder="Content" class="new-post-content" bind:value={newPostContent} />
        <hr>
        <button type="submit" class="new-post-submit">Post</button>
    </form>
</div>

<style>
    .new-post-container {
        margin: 2rem auto;
    }

    .new-post-form {
        width: 100%;
        padding: 0.4rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .new-post-form > hr {
        width: 100%;
        margin-top: 0;
    }

    .new-post-title {
        width: 100%;
        border: 2px solid transparent;
        color: var(--primary-dark);
        font-size: 1.5rem;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .new-post-content {
        min-height: 5rem;
        width: 100%;
        border: 2px solid transparent;
        color: var(--primary-dark);
        font-size: 1rem;
        overflow-wrap: break-word;
        resize: none;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .new-post-title:focus, .new-post-content:focus {
        outline: none;
        border: 2px solid #ccc;
        padding: -1px;
    }
</style>