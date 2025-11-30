<?php
// Mot de passe en clair que vous souhaitez hacher
$password = 'pass123';

// Génération du hash sécurisé avec l'algorithme par défaut (bcrypt)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Affichez le hash généré
echo "Voici votre mot de passe haché : " . $hashedPassword . PHP_EOL;
