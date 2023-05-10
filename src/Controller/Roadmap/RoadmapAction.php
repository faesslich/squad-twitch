<?php

namespace App\Controller\Roadmap;

use App\Form\StreamerSelectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(data: '/roadmap', name: 'roadmap')]
class RoadmapAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(type: StreamerSelectType::class);
        $form->handleRequest(request: $request);
        if ($form->isSubmitted() && $form->isValid()) {
            $values = array_values(array_filter($form->getData()));

            return $this->redirectToRoute(
                route: 'watch',
                parameters: [
                    'slug1' => $values[0] ?? null,
                    'slug2' => $values[1] ?? null,
                    'slug3' => $values[2] ?? null,
                    'slug4' => $values[3] ?? null
                ]
            );
        }

        $roadmap = [
            [
                'label' => 'User-Verwaltung',
                'value' => 'In naher Zukunft wird es möglich sein, bei SquadTwitch ein Konto zu erstellen. Dies ermöglicht es uns, ein personalisiertes Erlebnis für euch zu schaffen. Wir werden in den folgenden Entwicklungsplänen näher darauf eingehen, was dies beinhaltet.',
                'implemented' => false,
                'development' => true
            ],
            [
                'label' => 'Dark- & Light-Mode',
                'value' => 'Das Sparen von Strom ist zwar wichtig, aber die Präferenz für die Darstellung einer Website ist noch wichtiger. Deshalb könnt ihr bald zwischen einem Light-Mode und einem Dark-Mode wählen und entscheiden, welche Darstellung euch besser gefällt und diese festlegen.',
                'implemented' => false,
                'development' => false
            ],
            [
                'label' => 'Erstellen eigener Layouts',
                'value' => 'Es gibt Situationen, in denen nur der Haupt-Stream wichtig ist und die anderen Streams in kleinerer Größe ausreichend sind, zum Beispiel beim Anschauen eines Turniers eures Lieblingsspiels. In diesem Fall könnt ihr den Haupt-Stream groß darstellen und einige der Teilnehmer-Streams klein daneben positionieren, um nichts zu verpassen. Wir planen, dass ihr selbst bestimmen könnt, wie die Streams dargestellt werden und wo sie auf der Seite platziert werden sollen.',
                'implemented' => false,
                'development' => false
            ],
            [
                'label' => 'Drag- & Drop der Stream-Fenster',
                'value' => 'Wir wollen euch die Möglichkeit geben die Stream-Fenster beliebig zu verschieben oder zu überlagern.',
                'implemented' => false,
                'development' => false
            ],
            [
                'label' => 'Login über Twitch',
                'value' => 'Jetzt wird es interessant, oder nicht? Wir planen, dass ihr euch direkt mit eurem Twitch-Account anmelden könnt, ohne eine zusätzliche Registrierung durchzuführen. Dies ist auch für zukünftige geplante Funktionen erforderlich. Details dazu in den folgenden Punkten.',
                'implemented' => false,
                'development' => true
            ],
            [
                'label' => 'Twitch-Account verknüpfen',
                'value' => 'Wenn ihr euch bereits bei SquadTwitch registriert habt, aber die Vorteile von Twitch nutzen möchtet, werdet ihr in der Lage sein, nachträglich euren Twitch-Account mit eurem SquadTwitch-Account zu verknüpfen, um alle Funktionen nutzen zu können.',
                'implemented' => false,
                'development' => true
            ],
            [
                'label' => 'Stream-Auswahl aus Follower-Liste',
                'value' => 'Ihr seid kein Fan vom Tippen? Kein Problem! Wenn ihr euren Twitch-Account mit SquadTwitch verknüpft habt, werdet ihr in Zukunft einfach Streamer aus eurer Follow-Liste auswählen können, ohne etwas eingeben zu müssen.',
                'implemented' => false,
                'development' => false
            ],
            [
                'label' => 'Favoriten!',
                'value' => 'Schaut ihr oft die gleichen Streamer parallel? Zukünftig werdet ihr in der Lage sein, bereits kombinierte Streams zu euren Favoriten hinzuzufügen, so dass ihr sie nicht jedes Mal neu auswählen müsst.',
                'implemented' => false,
                'development' => false
            ],
            [
                'label' => 'Chat-Enthusiasten-Modus',
                'value' => 'Schaut ihr weniger Streams, aber schreibt dafür umso mehr im Chat? Wir planen einen "Chat-Enthusiasten-Modus", in dem ihr alle Chats der ausgewählten Streams gleichzeitig sehen und nutzen könnt. Auf diese Weise könnt ihr in allen Streams gleichzeitig chatten und verpasst nichts!',
                'implemented' => false,
                'development' => false
            ]
        ];

        return $this->render(
            view: '@App/page/roadmap/roadmap.html.twig',
            parameters: [
                'form' => $form->createView(),
                'roadmap' => $roadmap
            ],
        );
    }
}
