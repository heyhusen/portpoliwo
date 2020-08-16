<template>
  <div class="box">
    <AddButton :to="{ name: 'social-media-create' }" />
    <b-button
      v-if="checkedRows.length"
      type="is-danger"
      size="is-small"
      icon-left="delete"
      rounded
      @click="deleteData"
    >
      <span>Delete</span>
    </b-button>
    <hr />
    <b-table
      :data="data"
      :loading="loading"
      :checked-rows.sync="checkedRows"
      checkable
      :checkbox-position="checkboxPosition"
      paginated
      hoverable
      scrollable
      backend-pagination
      :total="total"
      :per-page="perPage"
      aria-next-label="Next page"
      aria-previous-label="Previous page"
      aria-page-label="Page"
      aria-current-label="Current page"
      backend-sorting
      :default-sort-direction="defaultSortOrder"
      :default-sort="[sortField, sortOrder]"
      @page-change="onPageChange"
      @sort="onSort"
    >
      <template slot="empty">
        <div class="has-text-centered">Empty</div>
      </template>
      <template slot-scope="props">
        <b-table-column field="created_at" label="Created At" sortable centered>
          {{
            props.row.created_at
              ? new Date(props.row.created_at).toLocaleString()
              : 'unknown'
          }}
        </b-table-column>

        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="icon" label="Icon" sortable>
          <b-icon :icon="props.row.icon"></b-icon>
        </b-table-column>

        <b-table-column field="url" label="URL" sortable>
          <a :href="props.row.url" target="_blank">{{ props.row.url }}</a>
        </b-table-column>

        <b-table-column field="action" label="Action">
          <ActionButton
            :edit="false"
            :detail-to="{
              name: 'social-media-show',
              params: { id: props.row.id },
            }"
          />
        </b-table-column>
      </template>
      <template slot="bottom-left">
        <b>Total checked</b>: {{ checkedRows.length }}
      </template>
    </b-table>
  </div>
</template>

<script>
import { datatableMixin } from '@/js/mixins/datatable'

export default {
  name: 'SocialMediaIndex',
  metaInfo: {
    title: 'Social Media',
  },
  mixins: [datatableMixin],
  data() {
    return {
      url: 'social-media',
    }
  },
}
</script>
