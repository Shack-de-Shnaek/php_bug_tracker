<script>
    import { modalControl, viewedProject } from '../../../store';
    import NumberField from "../../misc/NumberField.svelte";
    import TextArea from "../../misc/TextArea.svelte";
    import TextField from "../../misc/TextField.svelte";

    let updateName = $viewedProject.name;
    let updateDescription = $viewedProject.description;
    let updateProgress = $viewedProject.progress;
    let updateStatus = $viewedProject.status; 

    const handleUpdate = async(type) => {
        let data = `${encodeURIComponent('id')}=${encodeURIComponent($viewedProject.idProject)}&${encodeURIComponent('column')}=${encodeURIComponent(type)}`;
        let value;
        switch (type) {
            case 'name':
                value = updateName
                break;
            case 'description':
                value = updateDescription
                break;
            case 'status':
                value = updateStatus
                break;
            case 'progress':
                value = updateProgress
                break;
        }
        data += `&${encodeURIComponent('value')}=${encodeURIComponent(value)}`;
        try {
            const res = await fetch('/api/projects', {
                method: 'PUT',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Project info has been updated');
                viewedProject.update(project => {
                    project[type] = value;
                    return project; 
                });
            } else modalControl.show('error', 'Could not update project info');
        } catch(e) {
            modalControl.show('error', 'Could not update project info');
            console.log(e);
        }
    }
</script>

<div class="project-settings-container general-container">
    <form class="new-name-form update-form" on:submit|preventDefault={() => handleUpdate('name')}>
        <TextField minLength="5" maxLength="45" label="Name" bind:value={updateName} />
        <button type="submit" class="save-button">Save</button>
    </form>
    <form class="new-description-form update-form" on:submit|preventDefault={() => handleUpdate('description')}>
        <TextArea rows="10" minLength="20" label="Description" bind:value={updateDescription} />
        <button type="submit" class="save-button">Save</button>
    </form>
    <form class="new-progress-form update-form" on:submit|preventDefault={() => handleUpdate('progress')}>
        <NumberField label="Progress %" bind:value={updateProgress} />
        <button type="submit" class="save-button">Save</button>
    </form>
    <form class="new-status-form update-form" on:submit|preventDefault={() => handleUpdate('status')}>
        <select required class="new-project-status" bind:value={updateStatus} on:input={(e) => e.target.blur()}>
            <option value="on hiatus">On hiatus</option>
            <option value="active development">Active development</option>
            <option value="finished">Finished</option>
        </select>
        <button type="submit" class="save-button">Save</button>
    </form>
</div>

<style>
    .project-settings-container {
        margin: 2rem auto;
        display: flex;
        flex-direction: column;
    }
    
    .update-form {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .new-project-status {
        background-color: var(--primary-white);
        padding: 5px;
        border: 2px solid #ccc;
        border-radius: 5px;
        outline: none;
    }

    .new-project-status > option:hover {
        background-color: var(--primary-blue);
    }
</style>