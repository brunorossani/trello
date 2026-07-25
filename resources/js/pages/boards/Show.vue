<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import type { Team } from '@/types';
import { show as boardShow } from '@/routes/boards';

const page = usePage();
const currentTeam = page.props.currentTeam as Team | null;

const props = defineProps<{
  board: {
    id: number;
    name: string;
    slug: string;
    isPrivate: boolean;
    lists: Array<{
      id: number;
      name: string;
      cards: Array<{
        id: number;
        title: string;
        description: string | null;
        dueAt: string | null;
        members: Array<{
          id: number;
          name: string;
        }>;
      }>;
    }>;
  };
}>();

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
});
</script>

<template>
  <Head :title="board.name" />

  <div v-if="board.lists.length" class="overflow-x-auto p-4">
    <div class="flex min-w-max items-start gap-4">
      <section
        v-for="list in board.lists"
        :key="list.id"
        class="w-[280px] rounded-3xl border border-sidebar-border/70 bg-muted/10 p-3 shadow-sm"
      >
        <div class="mb-3 flex items-center justify-between gap-3">
          <h2 class="text-sm font-semibold">{{ list.name }}</h2>
          <span class="rounded-full bg-background px-2 py-1 text-[11px] font-medium text-muted-foreground">
            {{ list.cards.length }}
          </span>
        </div>

        <div class="space-y-3">
          <article
            v-for="card in list.cards"
            :key="card.id"
            class="rounded-3xl border border-border bg-card p-4 shadow-sm"
          >
            <div class="flex items-start justify-between gap-3">
              <p class="text-sm font-semibold">{{ card.title }}</p>
              <span v-if="card.dueAt" class="text-[11px] text-muted-foreground">
                {{ card.dueAt }}
              </span>
            </div>

            <p v-if="card.description" class="mt-2 text-sm text-muted-foreground line-clamp-2">
              {{ card.description }}
            </p>

            <div v-if="card.members.length" class="mt-3 flex flex-wrap gap-2">
              <span
                v-for="member in card.members"
                :key="member.id"
                class="rounded-full bg-primary/10 px-2 py-1 text-[11px] font-medium text-primary-foreground"
              >
                {{ member.name }}
              </span>
            </div>
          </article>
        </div>
      </section>
    </div>
  </div>
</template>
