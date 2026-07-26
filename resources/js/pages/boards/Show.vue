<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Plus } from '@lucide/vue'
import type { Team } from '@/types'
import { show as boardShow } from '@/routes/boards'
import CreateBoardListModal from '@/components/trello/CreateBoardListModal.vue'
import BoardListColumn from '@/components/trello/BoardListColumn.vue'

const props = defineProps<{
  board: {
    id: number
    name: string
    slug: string
    isPrivate: boolean
    lists: Array<{
      id: number
      name: string
      cards: Array<{
        id: number
        title: string
        description: string | null
        dueAt: string | null
        members: Array<{ id: number; name: string }>
      }>
    }>
  }
}>()

defineOptions({
  layout: (layoutProps: { board: { name: string; slug: string }; currentTeam?: Team | null }) => ({
    breadcrumbs: [
      {
        title: layoutProps.board.name,
        href: layoutProps.currentTeam
          ? boardShow({ current_team: layoutProps.currentTeam.slug, board: layoutProps.board.slug })
          : '#',
      },
    ],
  }),
})
</script>

<template>

  <Head :title="board.name" />

  <div v-if="board.lists.length" class="overflow-x-auto overflow-y-hidden p-4" style="height: calc(100dvh - 80px)">
    <div class="flex h-full min-w-max items-start gap-3">
      <BoardListColumn v-for="list in board.lists" :key="list.id" :list="list" />

      <CreateBoardListModal>
        <template #trigger>
          <button type="button"
            class="flex w-[300px] shrink-0 items-center gap-2 rounded-2xl border border-dashed border-sidebar-border/70 px-4 py-3 text-sm text-muted-foreground transition-colors hover:border-sidebar-border hover:bg-muted/20 hover:text-foreground">
            <Plus class="h-4 w-4" />
            Add list
          </button>
        </template>
      </CreateBoardListModal>
    </div>
  </div>

  <div v-else class="flex flex-col items-center justify-center p-8 text-center" style="height: calc(100dvh - 57px)">
    <div class="rounded-2xl border border-dashed border-sidebar-border/70 bg-muted/10 p-10">
      <p class="mb-4 text-sm text-muted-foreground">No lists yet. Create one to get started.</p>
      <CreateBoardListModal />
    </div>
  </div>
</template>