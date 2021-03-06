<?php
$this->Breadcrumb->add(__d('admin', 'Reports'), array('controller' => 'reports', 'action' => 'index'));

$this->Paginator->options(array('url' => $this->params['named']));

if ($this->request->data[$model->alias]['status'] == ItemReport::PENDING) {
	$fieldsToShow = array('id', 'status', 'type', 'model', 'item', 'reporter_id', 'reason', 'created');
	$pageTitle = __d('admin', 'Pending Reports');
} else {
	$fieldsToShow = array('id', 'status', 'type', 'model', 'item', 'resolver_id', 'comment', 'created', 'modified');
	$pageTitle = __d('admin', 'Resolved Reports');
}  ?>

<div class="action-buttons">
	<?php echo $this->element('button/filter'); ?>
</div>

<h2><?php echo $this->Admin->outputIconTitle($model, $pageTitle); ?></h2>

<?php
echo $this->element('filters');

echo $this->Form->create($model->alias, array('class' => 'form-horizontal'));
echo $this->element('pagination'); ?>

	<table id="table" class="table table-striped table-bordered table-hover sortable clickable">
		<thead>
			<tr>
				<?php foreach ($fieldsToShow as $field) { ?>
					<th class="col-<?php echo $field; ?>">
						<?php echo $this->Paginator->sort($field, $model->fields[$field]['title']); ?>
					</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php if ($results) {
				foreach ($results as $result) {
					$id = $result[$model->alias][$model->primaryKey];
					$object = $this->Admin->introspect($result[$model->alias]['model']); ?>

					<tr>
						<?php foreach ($fieldsToShow as $field) {
							$data = $model->fields[$field];

							if ($field === 'id') { ?>

								<td class="col-id">
									<?php echo $this->Html->link($id, array('action' => 'read', $id), array('class' => 'click-target')); ?>
								</td>

							<?php } else if ($field === 'item') { ?>

								<td class="col-item">
									<?php
									$title = $result[$model->alias]['item'];

									if (!$title) {
										$title = $result[$model->alias]['foreign_key'];
									}

									echo $this->Html->link($title, array('action' => 'read', $id)); ?>
								</td>

							<?php } else {
								echo $this->element('field_cell', array(
									'result' => $result,
									'field' => $field,
									'data' => $model->fields[$field]
								));
							}
						} ?>

					</tr>

				<?php }
			} else { ?>

			<tr>
				<td colspan="<?php echo count($fieldsToShow); ?>" class="no-results">
					<?php echo __d('admin', 'No results to display'); ?>
				</td>
			</tr>

			<?php } ?>
		</tbody>
	</table>

<?php
echo $this->element('pagination');
echo $this->Form->end();