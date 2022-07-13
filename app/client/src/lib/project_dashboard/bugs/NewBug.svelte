<script>
    import { currentUser, viewedProject, modalControl } from '../../../store';

    let newBugName = '';
    let newBugDescription = '';
    let newBugSeverity = 'minor';

    const uploadBug = async() => {
        try {
            const data = new FormData();
            data.append('name', newBugName);
            data.append('description', newBugDescription);
            data.append('severity', newBugSeverity);
            data.append('submitterId', $currentUser.idUser);
            data.append('projectId', $viewedProject.idProject);
            const res = await fetch('/api/bugs', {
                method: 'POST',
                body: data
            });
            if(res.ok) {
                const json = await res.json();
                modalControl.show('success', 'Bug submitted');
                viewedProject.update(value => {
                    value.bugs = [json[0], ...value.bugs];
                    return value;
                });
                currentUser.update(value => {
                    value.bugs.submitted = [json[0], ...value.bugs.submitted];
                    return value;
                });
                newBugName = '';
                newBugDescription = '';
                newBugSeverity = '';
            } else {
                modalControl.show('error', 'Could not uplaod the bug');
            }
        } catch(e) {
            modalControl.show('error', 'Could not uplaod the bug');
            console.log(e);
        }
    }
</script>

<div class="new-bug-container general-container">
    <form class="new-bug-form" on:submit|preventDefault={uploadBug}>
        <input maxlength="45" required type="text" name="title" class="new-bug-title" placeholder="Title" bind:value={newBugName} />
        <textarea  required name="description" cols="30" rows="3" placeholder="Description" class="new-bug-description" bind:value={newBugDescription} />
        <select required class="new-bug-severity" bind:value={newBugSeverity} on:input={(e) => e.target.blur()}>
            <option value="cosmetic">Cosmetic</option>
            <option value="convenience">Convenience</option>
            <option value="minor">Minor</option>
            <option value="medium">Medium</option>
            <option value="major">Major</option>
            <option value="fatal">Fatal</option>
        </select>
        <hr>
        <button type="submit" class="new-bug-submit">Post</button>
    </form>
</div>

<style>
    .new-bug-container {
        margin: 2rem auto;
    }

    .new-bug-form {
        width: 100%;
        padding: 0.4rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .new-bug-form > hr {
        width: 100%;
        margin-top: 0;
    }

    .new-bug-title {
        width: 100%;
        border: 2px solid transparent;
        color: var(--primary-dark);
        font-size: 1.5rem;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .new-bug-description {
        min-height: 5rem;
        width: 100%;
        border: 2px solid transparent;
        color: var(--primary-dark);
        font-size: 1rem;
        overflow-wrap: break-word;
        resize: none;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .new-bug-title:focus, .new-bug-description:focus {
        outline: none;
        border: 2px solid #ccc;
        padding: -1px;
    }

    .new-bug-severity {
        background-color: var(--primary-white);
        padding: 5px;
        border: 2px solid #ccc;
        border-radius: 5px;
        outline: none;
    }

    .new-bug-severity > option:hover {
        background-color: var(--primary-blue);
    }
</style>