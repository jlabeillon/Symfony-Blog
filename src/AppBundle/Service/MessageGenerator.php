<?php

namespace AppBundle\Service;

class MessageGenerator
{
    public function getHappyMessage()
    {
        $messages = [
            "Vous êtes foutus, à priori, ce n'est pas un simple kyst.",
            "Votre femme couche avec son prof de gym. Oui, un de plus.",
            "On sait où vous avez enterré le corps.",
            "L'adulte que vous êtes devenu ferait honte à l'enfant que vous étiez.",
            "Celui qui lit ça est un con.",
            "La jolie blonde d'hier soir s'appelle Didier.",
            "L'assassin de Kennedy est....",
            "Chance: Voici les numéros du tirage du loto d'hier 3, 35, 42, 17, 22, 7.",
            "Demandez à Papa où il était le jour de votre conception.",
            "Tout le monde sait que vous êtes un malade des films porno.",
            "C'est officiel, personne ne vous aime. Je sais, c'est douloureux.",
            "Ce gâteau était périmé depuis un moment. Comme le reste du repas.",
            "Coiffe toi comme François Fillon et tu trouveras le bonheur...",
            "Mourir à 32 ans, c'est pas si grave.",
            "Le gaz toxique se propagera dès que ce cookie sera brisé...",
            "Ne fais aucun geste brusque, pose ton portefeuille sur la table et personne ne sera blessé.",
            "Demain sera surement pire.",
            "Tu veux connaître ton avenir ? Envoie 'avenir' au 8 22 22 (* 3,5 euros par minutes, hors coût du SMS).",
            "La France perdra 8-7 contre les All Black, pas la peine de te lever demain matin.",
            "Preum's.",
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}
