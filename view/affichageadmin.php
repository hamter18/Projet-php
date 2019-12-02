<main role="main" class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5"> Page administration :</h1>
        <h3> Liste des articles :</h3>
        <form method="POST" action="admin.php?action=admin">
            <input type="search" name="recherche" class="form-control" placeholder="Recherche un article ..." />
            <button type="submit" class="btn btn-lg btn-primary btn-block">Search</button>
        </form>

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">titre</th>
                    <th scope="col">contenu</th>
                    <th scope="col">date_creation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($articles->rowCount() > 0) {
                    while ($a = $articles->fetch()) {
                        $reponse = $bdd->prepare('select id,contenu,titre,date_creation from billets where titre=? order by date_creation');
                        $reponse->execute(array($a['titre']));
                        while ($donnees = $reponse->fetch()) {
                            echo
                                '<tr>
                            <td>' . $donnees['id'] . '</td>
                            <td>' . $donnees['titre'] . '</td>
                            <td style="max-height: 2em;
                            max-width: 30em;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            white-space: nowrap;">' . $donnees['contenu'] . '</td>
                            <td>' . $donnees['date_creation'] . '</td>
                            <td><form method="POST" action="admin.php?action=delArticle&id_billet=' . $donnees['id'] . '"> <input type="submit" value="Supprimer cette article"/></form></td>
                            <td><form method="POST" action="admin.php?action=modifierNews&id_billet=' . $donnees['id'] . '"> <input type="submit" value="Modifier cette article"/></form></td>
                        </tr>';
                        }
                        $reponse->closeCursor();
                    }
                } else {
                    echo 'Aucun résultat pour: ' . $recherche . '...';
                }
                ?>
            </tbody>
        </table>
        <?php
        echo '<form method="POST" action="admin.php?action=ajouterNews"> <input type="submit" value="Ajouter un article"/></form>';
        ?>
        <h3> Liste des commentaires :</h3>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">id_billets</th>
                    <th scope="col">titre_billets</th>
                    <th scope="col">#</th>
                    <th scope="col">auteur</th>
                    <th scope="col">commentaire</th>
                    <th scope="col">date_commentaire</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($donnees = $comments->fetch()) {
                    echo
                        '<tr>
                        <td>' . $donnees['id_billet'] . '</td>
                        <td>' . $donnees['titre'] . '</td>
                        <td>' . $donnees['id'] . '</td>
                        <td>' . $donnees['auteur'] . '</td>
                        <td>' . $donnees['commentaire'] . '</td>
                        <td>' . $donnees['date_commentaire'] . '</td>
                        <td><form method="POST" action="admin.php?id_commentaire=' . $donnees['id'] . '&action=delCommentaire"> <input type="submit" value="Supprimer ce commentaire"/></form></td>
                    </tr>';
                }
                $comments->closeCursor();
                ?>
            </tbody>
        </table>
        <h3> Liste des membres :</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>#</td>
                    <td>pseudo</td>
                    <td>email</td>
                    <td>date_inscription</td>
                    <td>satus</td>
                    <td>commentaire</td>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($donnees = $membre->fetch()) {
                    echo
                        '<tr>
                        <td>' . $donnees['id'] . '</td>
                        <td>' . $donnees['pseudo'] . '</td>
                        <td>' . $donnees['email'] . '</td>
                        <td>' . $donnees['date_inscription'] . '</td>
                        <td>' . $donnees['statu'] . '</td>';
                    $req = $bdd->prepare('select commentaire from commentaires where auteur=?');
                    $req->execute(array($donnees['pseudo']));
                    while ($data = $req->fetch()) {
                        echo '<td>' . $data['commentaire'] . '</td>';
                    }
                    $req->closeCursor();

                    echo '<td><form method="POST" action="admin.php?action=delMembre&id_membre=' . $donnees['id'] . '&pseudo=' . $donnees['pseudo'] . '"> <input type="submit" value="Supprimer ce membre"/></form></td>
                        <td><form method="POST" action="admin.php?action=bannirMembre&id_membre=' . $donnees['id'] . '&pseudo=' . $donnees['pseudo'] . '"> <input type="submit" value="Bannir ce membre"/></form></td>
                    </tr>';
                }
                $membre->closeCursor();
                ?>
            </tbody>
        </table>
    </div>
</main>