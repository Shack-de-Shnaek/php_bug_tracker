<script>
    import { modalControl, viewedProject } from '../../../store';

    let newUserEmail = '';

    const uploadPost = async() => {
        try {
            const data = new FormData();
            data.append('id', $viewedProject.idProject);
            data.append('email', newUserEmail);
            const res = await fetch('/api/projects/members', {
                method: 'POST',
                body: data
            });
            if(res.ok) {
                const json = await res.json();
                modalControl.show('success', 'User added to the project');
                viewedProject.update(value => {
                    value.members = [...value.members, json[0]];
                    return value;
                });
            } else {
                modalControl.show('error', 'Could not add the user. Either there was an error or that user does not exist.');
            }
        } catch(e) {
            modalControl.show('error', 'Could not add the user. Either there was an error or that user does not exist.');
            console.log(e);
        }
    }
</script>

<div class="new-member-container general-container">
    <form class="new-member-form" on:submit|preventDefault={uploadPost}>
        <input type="email" name="email" class="new-member-email" placeholder="Email" bind:value={newUserEmail} />
        <hr>
        <button type="submit" class="new-member-submit">Add</button>
    </form>
</div>

<style>
    .new-member-container {
        margin: 2rem auto;
    }

    .new-member-form {
        width: 100%;
        padding: 0.4rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .new-member-form > hr {
        width: 100%;
        margin-top: 0;
    }

    .new-member-email {
        width: 100%;
        border: 2px solid transparent;
        color: var(--primary-dark);
        font-size: 1.5rem;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .new-member-email:focus {
        outline: none;
        border: 2px solid #ccc;
        padding: -1px;
    }
</style>