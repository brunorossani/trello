<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import type { Team, TrelloBoard } from '@/types';
import { computed } from 'vue';
import { show as boardShow } from '@/routes/boards';

const props = defineProps<{
  boards: TrelloBoard[];
}>();

const page = usePage();
const currentTeam = page.props.currentTeam as Team | null;
  
const activeBoard = computed(() => props.boards[0] ?? null);

const boardUrl = (board: TrelloBoard) => {
  if (!board.slug || !currentTeam?.slug) {
    return '#';
  }

  return boardShow({ current_team: currentTeam.slug, board: board.slug });
};
</script>

<template>
  <div class="space-y-6">
    <div v-if="!activeBoard" class="rounded-xl border border-sidebar-border/70 bg-background p-8 text-center text-sm text-muted-foreground">
      No board available for this team yet.
    </div>

    <div v-else class="space-y-4">
      <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <div
          v-for="board in props.boards"
          :key="board.id"
          class="space-y-3 rounded-3xl border border-sidebar-border/70 bg-background p-4 shadow-sm"
        >
          <div class="flex items-center justify-between gap-2">
            <div>
              <h2 class="text-lg font-semibold">
                <Link :href="boardUrl(board)">{{ board.name }}</Link>
              </h2>
              <p class="text-sm text-muted-foreground">
                {{ board.lists.length }} lists • {{ board.lists.reduce((count, list) => count + list.cards.length, 0) }} cards
              </p>
            </div>
            <span class="rounded-full bg-accent/10 px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-accent-foreground">
              {{ board.isPrivate ? 'Private' : 'Public' }}
            </span>
          </div>

          <div class="grid gap-3 xl:grid-cols-2">
            <div v-for="list in board.lists" :key="list.id" class="rounded-3xl bg-muted/10 p-3">
              <div class="mb-3 flex items-center justify-between gap-3">
                <h3 class="text-sm font-semibold">{{ list.name }}</h3>
                <span class="rounded-full bg-background px-2 py-1 text-[11px] font-medium text-muted-foreground">
                  {{ list.cards.length }}
                </span>
              </div>

              <div class="space-y-3">
                <div
                  v-for="card in list.cards"
                  :key="card.id"
                  class="rounded-3xl border border-border bg-card p-4 shadow-sm"
                >
                  <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold">{{ card.title }}</p>
                    <span class="text-xs text-muted-foreground">{{ card.dueAt ?? 'No date' }}</span>
                  </div>
                  <p class="mt-2 text-sm text-muted-foreground line-clamp-2">{{ card.description ?? 'No description' }}</p>
                  <div class="mt-3 flex flex-wrap gap-2">
                    <span
                      v-for="member in card.members"
                      :key="member.id"
                      class="rounded-full bg-primary/10 px-2 py-1 text-[11px] font-medium text-primary-foreground"
                    >
                      {{ member.name }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
