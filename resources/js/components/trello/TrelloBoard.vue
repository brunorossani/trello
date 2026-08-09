<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Globe, Lock } from '@lucide/vue';
import { computed } from 'vue';
import MemberAvatar from '@/components/trello/MemberAvatar.vue';
import { boardAccent } from '@/lib/colors';
import { show as boardShow } from '@/routes/boards';
import type { Team, TrelloBoard } from '@/types';

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
          class="overflow-hidden rounded-3xl border border-sidebar-border/70 bg-background shadow-sm transition-shadow hover:shadow-md"
        >
          <div
            class="h-2.5"
            :style="{ background: `linear-gradient(90deg, ${boardAccent(board.id).from}, ${boardAccent(board.id).to})` }"
          />

          <div class="space-y-3 p-4">
            <div class="flex items-center justify-between gap-2">
              <div>
                <h2 class="text-lg font-semibold">
                  <Link :href="boardUrl(board)">{{ board.name }}</Link>
                </h2>
                <p class="text-sm text-muted-foreground">
                  {{ board.lists.length }} lists • {{ board.lists.reduce((count, list) => count + list.cards.length, 0) }} cards
                </p>
              </div>
              <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-muted px-2.5 py-1 text-[11px] font-medium text-muted-foreground">
                <Lock v-if="board.isPrivate" class="h-3 w-3" />
                <Globe v-else class="h-3 w-3" />
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
                    <p class="mt-2 line-clamp-2 text-sm text-muted-foreground">{{ card.description ?? 'No description' }}</p>
                    <div v-if="card.members.length" class="mt-3 flex -space-x-2">
                      <MemberAvatar v-for="member in card.members" :key="member.id" :member="member" size="xs" />
                    </div>
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
