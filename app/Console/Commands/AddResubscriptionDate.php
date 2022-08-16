<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddResubscriptionDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resubscription:date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $list = [
            ['lastname' => "BAISAMY", 'firstname' => "Jean-David"],
            ['lastname' => "BALFOUR", 'firstname' => "CLARKE Heather"],
            ['lastname' => "BAUDET", 'firstname' => "Jean-François"],
            ['lastname' => "BERNARDINI", 'firstname' => "Donatella"],
            ['lastname' => "BOETTGE", 'firstname' => "Frédérique"],
            ['lastname' => "BONNAVENTURE", 'firstname' => "Pierre"],
            ['lastname' => "BORGATO", 'firstname' => "Amelie"],
            ['lastname' => "BOUCHET", 'firstname' => "Christine"],
            ['lastname' => "BOUCHET", 'firstname' => "Catherine"],
            ['lastname' => "BRONCIN", 'firstname' => "Marie-Dominique / MarieDo"],
            ['lastname' => "BULLAT", 'firstname' => "Rachel"],
            ['lastname' => "CATRY", 'firstname' => "Chantal"],
            ['lastname' => "COGNIOUL", 'firstname' => "Cédric"],
            ['lastname' => "COMBRE", 'firstname' => "Marie-Claude"],
            ['lastname' => "COSTA", 'firstname' => "Karine"],
            ['lastname' => "DAUDIN", 'firstname' => "Elisabeth"],
            ['lastname' => "DEBULLE", 'firstname' => "Jocelyne"],
            ['lastname' => "DEGOURNAY", 'firstname' => "LAMY Nathalie"],
            ['lastname' => "DELLA-BALDA", 'firstname' => "Pascal"],
            ['lastname' => "DELLA-BALDA", 'firstname' => "Florence"],
            ['lastname' => "DENAMBRIDE", 'firstname' => "Michèle"],
            ['lastname' => "DESVALLEE", 'firstname' => "Pascale"],
            ['lastname' => "DEVENOGES", 'firstname' => "Anouk"],
            ['lastname' => "DON", 'firstname' => "Anne"],
            ['lastname' => "DON", 'firstname' => "Yves"],
            ['lastname' => "DUBOUT", 'firstname' => "Laurence"],
            ['lastname' => "DURET", 'firstname' => "Jeannine"],
            ['lastname' => "DUTOIT", 'firstname' => "MARIE-LAURE"],
            ['lastname' => "DUVAL", 'firstname' => "Léon"],
            ['lastname' => "DUVAL", 'firstname' => "Marie-Christine"],
            ['lastname' => "FARGNIER", 'firstname' => "Françoise"],
            ['lastname' => "FELLER", 'firstname' => "THOMAS Mauricette"],
            ['lastname' => "FEROUL", 'firstname' => "Maxime"],
            ['lastname' => "FEROUL", 'firstname' => "Nathalie"],
            ['lastname' => "FLATTRES", 'firstname' => "Sabine"],
            ['lastname' => "FOUILLET", 'firstname' => "Chantal"],
            ['lastname' => "GAL", 'firstname' => "Danielle"],
            ['lastname' => "GERST", 'firstname' => "Véronique"],
            ['lastname' => "GERST", 'firstname' => "Bernard"],
            ['lastname' => "GILLES", 'firstname' => "FOL Christiane"],
            ['lastname' => "GILLES", 'firstname' => "FOL Gilles"],
            ['lastname' => "GREGORIS", 'firstname' => "Jean-Marc"],
            ['lastname' => "GREGORIS", 'firstname' => "Isabelle"],
            ['lastname' => "GROS", 'firstname' => "Estelle"],
            ['lastname' => "GROS", 'firstname' => "Nathalie"],
            ['lastname' => "GROS", 'firstname' => "FOL Sylvie"],
            ['lastname' => "GRUFFAZ", 'firstname' => "Françoise"],
            ['lastname' => "GUILLAND", 'firstname' => "Marie"],
            ['lastname' => "GUYOT", 'firstname' => "Babeth"],
            ['lastname' => "HALDIMANN", 'firstname' => "Isabelle"],
            ['lastname' => "HALDIMANN", 'firstname' => "Alexandre"],
            ['lastname' => "LOFFEL", 'firstname' => "Jacqueline"],
            ['lastname' => "LOMETTI", 'firstname' => "Catherine"],
            ['lastname' => "MENU", 'firstname' => "Denise"],
            ['lastname' => "MIRA", 'firstname' => "D'ERCOLE Joelle"],
            ['lastname' => "MURAZ", 'firstname' => "Thierry pour les tests"],
            ['lastname' => "NEYROUD", 'firstname' => "Jeanine"],
            ['lastname' => "PENZ", 'firstname' => "Sylvie"],
            ['lastname' => "REINE", 'firstname' => "BONNAVENTURE Géraldine"],
            ['lastname' => "RIVIèRE", 'firstname' => "Alice"],
            ['lastname' => "ROCHER", 'firstname' => "Anne"],
            ['lastname' => "ROGUET", 'firstname' => "Michèle"],
            ['lastname' => "SAVIGNY", 'firstname' => "Elisabeth"],
            ['lastname' => "SCHULTZ", 'firstname' => "Nathalie"],
            ['lastname' => "SEGUIN", 'firstname' => "Paquerette"],
            ['lastname' => "SERAIN", 'firstname' => "Patricia"],
            ['lastname' => "SUBLET", 'firstname' => "Véronique"],
            ['lastname' => "TAGAND", 'firstname' => "CENA Claire"],
            ['lastname' => "THENARD", 'firstname' => "Catherine"],
            ['lastname' => "THIVOLLE", 'firstname' => "Odile"],
            ['lastname' => "TREMBLET", 'firstname' => "Clotilde"],
            ['lastname' => "VAUFREY", 'firstname' => "Véronique"],

        ];

        foreach ($list as $user) {
            DB::table('users')
                ->where($user)
                ->update(['resubscribed_at' => '2022-08-13']);
        }

        return 0;
    }
}
