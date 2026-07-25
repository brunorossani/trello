    <script setup lang="ts">
    import { Head, usePage } from '@inertiajs/vue3';
    import PendingInvitationsModal from '@/components/PendingInvitationsModal.vue';
    import CreateBoardModal from '@/components/trello/CreateBoardModal.vue'
    import TrelloBoard from '@/components/trello/TrelloBoard.vue';
    import { dashboard } from '@/routes';
    import type { DashboardInvitation, Team, TrelloBoard as TrelloBoardType } from '@/types';

    const page = usePage(); 

    const currentTeam = page.props.currentTeam as Team | null;

    defineProps<{
        pendingInvitations?: DashboardInvitation[];
        boards?: TrelloBoardType[];
    }>();

    defineOptions({
        layout: (props: { currentTeam?: Team | null }) => ({
            breadcrumbs: [
                {
                    title: 'Dashboard',
                    href: props.currentTeam
                        ? dashboard(props.currentTeam.slug)
                        : '/',
                },
            ],
        }),
    });
    </script>

    <template>
        <Head title="Dashboard" />

        <PendingInvitationsModal
            v-if="pendingInvitations && pendingInvitations.length > 0"
            :invitations="pendingInvitations"
        />

        <div class="flex min-h-full flex-1 flex-col gap-4 p-4">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">Dashboard</h1>
                        <p class="text-sm text-muted-foreground">
                            Create and manage boards for the current team.
                        </p>
                    </div>

                    <CreateBoardModal />
                </div>

                <TrelloBoard :boards="boards ?? []" />
            </div>
        </div>
    </template>
