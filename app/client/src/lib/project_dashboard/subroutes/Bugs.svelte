<script>
    import { userIsProjectMember, viewedProject } from '../../../store';
    import BugList from "../bugs/BugList.svelte";
    import NewBug from "../bugs/NewBug.svelte";
</script>

{#if $userIsProjectMember == true}
<NewBug />
{/if}
<BugList />