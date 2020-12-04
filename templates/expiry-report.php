<table>

  <thead>
    <tr>
      <th>User</th>
    </tr>
  </thead>

  <tbody>

    <?php foreach( $users as $user ) :



      ?>
      <tr>
        <td><?php print $user->ID; ?></td>
      </tr>
    <?php endforeach; ?>

  </tbody>

</table>
