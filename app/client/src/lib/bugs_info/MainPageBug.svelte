<script>
    import capitalizeFirst from '../../services/capitalizeFirst';
    import { currentUser, viewedUser, allUserBugs, modalControl } from '../../store';

    export let bug;
    export let project;

    const getUser = async(id) => {
        if(id == $currentUser.idUser) return $currentUser;
        if(id == $viewedUser.idUser) return $viewedUser;
        try {
            const res = await fetch(`/api/bugs/submitter?id=${bug.idBug}`);
            const json = await res.json();
            return json[0];
        } catch(e) {
            console.log(e);
            return {
                name: 'Error',
                surname: 'T-T'
            };
        }
    }

    const bugSeverityStyleLookup = (severity) => {
        switch(severity) {
            case 'minor':
                return 'minor';
            case 'cosmetic':
                return 'minor';
            case 'convenience':
                return 'minor';
            case 'medium':
                return 'medium';
            case 'major':
                return 'fatal';
            case 'fatal':
                return 'fatal';
        }
    }

    const bugStatusStyleLookup = (status) => {
        switch(status) {
            case 'submitted':
                return 'submitted';
            case 'being fixed':
                return 'active';
            case 'fixed':
                return 'done';
            case 'on hold':
                return 'paused';
        }
    }

    const handleDeleteBug = async() => {
        try {
            const data = `${encodeURIComponent('id')}=${encodeURIComponent(bug.idBug)}`;
            const res = await fetch(`/api/bugs`, {
                method: 'DELETE',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Bug deleted');
                allUserBugs.update(bugs => {
                    bugs = bugs.filter(filter => filter.idBug != bug.idBug);
                    return project;
                });
            } else {
                modalControl.show('error', 'Could not delete the bug');
            }
        } catch(e) {
            modalControl.show('error', 'Could not delete the bug');
            console.log(e);
        }
    }

    const handleFixBug = async() => {
        try {
            const data = new FormData();
            data.append('id', bug.idBug);
            data.append('fixerId', $currentUser.idUser);
            const res = await fetch(`/api/bugs/fix`, {
                method: 'POST',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Bug marked as fixed');
                viewedProject.update(project => {
                    for(const markBug of project.bugs) {
                        if(markBug.idBug === bug.idBug) {
                            markBug.status = 'fixed';
                            const date = new Date();
                            markBug.fixedAt = `${date.getUTCFullYear()}-${date.getUTCMonth()}-${date.getUTCDate()} ${date.getUTCHours()}:${date.getUTCMinutes()}:${date.getUTCSeconds()}`;
                            markBug.fixerId = $currentUser.idUser;
                            break;
                        }
                    }
                    return project;
                });
            } else {
                modalControl.show('error', 'Could not mark the bug as fixed');
            }
        } catch(e) {
            modalControl.show('error', 'Could not mark the bug as fixed');
            console.log(e);
        }
    }

    const handleMarkBugActive = async() => {
        try {
            const data = `${encodeURIComponent('id')}=${encodeURIComponent(bug.idBug)}&${encodeURIComponent('column')}=${encodeURIComponent('status')}&${encodeURIComponent('value')}=${encodeURIComponent('being fixed')}`;
            const res = await fetch(`/api/bugs`, {
                method: 'PUT',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Marked bug as being fixed');
                viewedProject.update(project => {
                    for(const markBug of project.bugs) {
                        if(markBug.idBug === bug.idBug) {
                            markBug.status = 'being fixed';
                            break;
                        }
                    }
                    return project;
                });
            } else {
                modalControl.show('error', 'Could not mark the bug as being fixed');
            }
        } catch(e) {
            modalControl.show('error', 'Could not mark the bug as being fixed');
            console.log(e);
        }
    }
</script>

<div class="bug-container general-container">
    <div class="bug-header">
        <div class="bug-title">{capitalizeFirst(bug.name)}</div>
        <div class="bug-project">In <a href={`/projects/${project.idProject}`}>{project.name}</a></div>
        <div class="bug-severity-status">
            <span class="bug-severity">Severity: 
                <span class="{bugSeverityStyleLookup(bug.severity)}">{bug.severity}</span>
            </span>
            <span class="bug-status">Status: 
                <span class="{bugStatusStyleLookup(bug.status)}">{bug.status}</span>
            </span>
        </div>
    </div>
    <hr>
    <div class="bug-description">
        {bug.description}
    </div>
    <hr>
    <div class="dates-credits">
        <div class="date-submitted">Submitted on: {bug.submittedAt}</div>
        {#await getUser(bug.submitterId)}
        Loading...
        {:then submitter} 
        <div class="credit-submitter">By: <a href="/users/{submitter.idUser}">{capitalizeFirst(submitter.name)} {capitalizeFirst(submitter.surname)}</a></div>
        {/await}
        {#if bug.fixerId}
            <div class="date-fixed">Fixed on: {bug.fixedAt}</div>
            {#await getUser(bug.fixerId)}
            Loading...
            {:then fixer} 
            <div class="credit-fixer">By: <a href="/users/{fixer.idUser}">{capitalizeFirst(fixer.name)} {capitalizeFirst(fixer.surname)}</a></div>
            {/await}
        {/if}
    </div>
    <hr>
    <div class="bug-controls">
        {#if bug.status === 'submitted' || bug.status === 'on hold'}
        <button class="mark-active" on:click={handleMarkBugActive}>I'm fixing this</button>
        {/if}
        {#if bug.status !== 'fixed'}
        <button class="mark-fixed" on:click={handleFixBug}>I fixed this</button>
        {/if}
        {#if $currentUser.idUser == project.leaderId || $currentUser.idUser == bug.submitterId}
        <button class="delete-bug" on:click={handleDeleteBug}>Delete this bug</button>
        {/if}
    </div>
</div>

<style>
    .bug-header {
        width: 100%;
        margin: 0.5rem 0;
        padding: 0 0.25rem;
    }

    .bug-title {
        font-size: 1.5rem;
        font-weight: 500;
    }

    .bug-severity-status {
        padding: 0.25rem;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-around;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .bug-severity > span, .bug-status > span {
        color: #000;
        padding: 3px;
        border-radius: 5px;
        font-size: 1rem;
        font-weight: 500;
    }

    .bug-description {
        padding: 0.5rem 0.25rem;
    }

    .minor {
        background-color: var(--secondary-yellow);
    }

    .medium {
        background-color: var(--secondary-orange);
    }

    .fatal {
        background-color: var(--secondary-red);
    }

    .submitted {
        background-color: var(--secondary-yellow);
    }

    .active {
        background-color: var(--primary-blue);
    }

    .paused {
        background-color: var(--secondary-orange);
    }

    .done {
        background-color: var(--secondary-green);
    }

    .bug-controls {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: 3rem;
        gap: 0.5rem;
    }

    .bug-controls > button {
        width: 100%;
        height: 90%;
        max-height: 100%;
        margin: 0 auto;
    }

    .mark-fixed {
        padding: 0.25rem;
        background-color: var(--secondary-green);
        grid-column: 1 / span 1;
        grid-row: 1;
    }

    .mark-active {
        padding: 0.25rem;
        background-color: var(--primary-blue);
        grid-column: 2 / span 1;
        grid-row: 1;
    }

    .delete-bug {
        padding: 0.25rem;
        background-color: var(--secondary-red);
        grid-column: 3 / span 1;
        grid-row: 1;
    }
</style>