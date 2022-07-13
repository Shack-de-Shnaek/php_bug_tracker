<script>
    import { userIsProjectLeader } from '../../../store';
    import MemberList from "../members/MemberList.svelte";
    import NewMember from "../members/NewMember.svelte";
</script>

{#if $userIsProjectLeader == true}
<NewMember />
{/if}
<MemberList />