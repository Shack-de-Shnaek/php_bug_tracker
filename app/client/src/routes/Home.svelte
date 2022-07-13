<script>
    import { currentUser, allUserBugs } from '../store';
    import HomeBugList from '../lib/home/HomeBugList.svelte';
    import HomePostList from '../lib/home/HomePostList.svelte';

    let bugs;
    currentUser.subscribe(value => {
        if(value.idUser !== 0) {
            bugs = {
                submitted: value.bugs.submitted.filter(filterBug => filterBug.status === 'submitted'),
                active: value.bugs.submitted.filter(filterBug => filterBug.status === 'being fixed'),
                fixed: value.bugs.fixed
            };
        }
    });

    const getBugCounts = async() => {
        if($allUserBugs.length > 0) return $allUserBugs;
        let bugCounts = {
            submitted: 0,
            active: 0,
            fixed: 0
        }
        for(const project of $currentUser.projects) {
            try {
                const res = await fetch(`/api/projects/bugs?id=${project.idProject}`);
                if(!res.ok) throw new Error(res);
                const json = await res.json();
                bugCounts.submitted += json.filter(filterBug => filterBug.status === 'submitted').length;
                bugCounts.active += json.filter(filterBug => filterBug.status === 'being fixed').length;
                bugCounts.fixed += json.filter(filterBug => filterBug.status === 'fixed').length;
            } catch(e) {
                throw new Error(e);
            }
        }
        return bugCounts;
    }
</script>

<svelte:head>
    <title>Home | Bug Tracker</title>
</svelte:head>
<main>
    <div class="post-page-header">
        <span class="title">Home</span>
    </div>
    <div class="bug-count-container">
        {#await getBugCounts()}
        Loading...
        {:then bugCounts} 
        <div class="bug-count submitted-bug-count">Open bugs: <b>{bugCounts.submitted}</b></div>
        <div class="bug-count active-bug-count">Active bugs: <b>{bugCounts.active}</b></div>
        <div class="bug-count fixed-bug-count">Fixed bugs: <b>{bugCounts.fixed}</b></div>            
        {/await}
    </div>
    <div class="lists-container">
        <HomePostList />
        <HomeBugList />
    </div>
</main>

<style>
    .post-page-header {
        min-width: 300px;
        width: 100%;
        height: fit-content;
        max-height: 5rem;
        padding: 0.5rem 0.75rem;
        font-size: clamp(1.25rem, 3vw, 2rem);
        border-bottom: 2px solid var(--primary-blue);
    }
    
    .bug-count-container {
        min-width: 300px;
        width: 100%;
        max-width: 1200px;
        margin: 2rem auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .bug-count {
        min-width: 250px;
        max-width: 450px;
        padding: 1rem 0.5rem;
        border-radius: 10px;
        text-align: center;
        font-size: 1.15rem;
    }

    .submitted-bug-count {
        background-color: var(--secondary-yellow);
    }

    .active-bug-count {
        background-color: var(--primary-blue);
    }

    .fixed-bug-count {
        background-color: var(--secondary-green);
    }

    .lists-container {
        min-width: 300px;
        width: 100%;
        max-width: 1200px;
        margin: 2rem auto;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: space-around;
        gap: 1rem;
        flex-wrap: wrap;
    }

    
    @media only screen and (max-width: 600px) {
        .bug-count {
            width: 90vw;
            max-width: fit-content;
            padding: 0.5rem;
        }
    }
</style>

