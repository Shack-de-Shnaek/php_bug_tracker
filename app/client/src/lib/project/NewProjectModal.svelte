<script>
    import { fade } from 'svelte/transition'
    import { currentUserId, modalControl } from '../../store';
    import TextArea from '../misc/TextArea.svelte';
    import TextField from '../misc/TextField.svelte';

    let visible = false;

    let newProjectName = '';
    let newProjectDescription = '';
    
    const handleCreateProject = async() => {
        const data = new FormData();
        data.append('name', newProjectName);
        data.append('description', newProjectDescription);
        data.append('leaderId', $currentUserId);
        try {
            const res = await fetch('/api/projects', {
                method: 'POST',
                body: data
            });
            console.log(res)
            if(res.ok) {
                const json = await res.json();
                modalControl.show('success', 'Project created');
                window.location.assign(`/projects/${json[0].idProject}`);
            } else {
                modalControl.show('error', 'Could not create the project');
                throw new Error();
            }
        } catch(e) {
            modalControl.show('error', 'Could not create the project');
            throw new Error(e);
        }
    }

    export const hide = () => visible = false;
    export const show = () => visible = true;
</script>

{#if visible}
<div class="new-project-modal" transition:fade on:click={hide}>
    <form class="new-project-form general-container" on:submit|preventDefault={handleCreateProject} on:click|stopPropagation={null}>
        <TextField label="Project name" minLength="5" maxLength="45" required="true" bind:value={newProjectName} />
        <TextArea label="Description"  rows="5" required="true" bind:value={newProjectDescription} />
        <button class="submit">Create</button>
    </form>
</div>
{/if}

<style>
    .new-project-modal {
        z-index: 100;
        width: 100vw;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background-color: rgba(50, 50, 50, 0.5);
        display: grid;
        place-items: center;
    }

    .new-project-form {
        z-index: 200;
        background-color: var(--primary-white);
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: 0.5rem;
    }
</style>